<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class SettingController extends Controller
{
    public function index()
    {
        $business = auth()->user()->business;
        $mailKeys = ['mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_name'];
        $settings = BusinessSetting::getMany($business->id, $mailKeys);

        // Desencriptar password para mostrar masked
        $settings['mail_password_set'] = !empty($settings['mail_password']);

        return view('admin.settings.index', compact('business', 'settings'));
    }

    public function updateBusiness(Request $request)
    {
        $business = auth()->user()->business;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'commercial_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
        ]);

        $business->update($validated);

        return back()->with('success', 'Datos del negocio actualizados.');
    }

    public function updateMail(Request $request)
    {
        $business = auth()->user()->business;

        $validated = $request->validate([
            'mail_username' => 'required|email|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_from_name' => 'nullable|string|max:255',
        ]);

        // Gmail SMTP hardcoded
        BusinessSetting::setValue($business->id, 'mail_host', 'smtp.gmail.com');
        BusinessSetting::setValue($business->id, 'mail_port', '587');
        BusinessSetting::setValue($business->id, 'mail_username', $validated['mail_username']);
        BusinessSetting::setValue($business->id, 'mail_encryption', 'tls');
        BusinessSetting::setValue($business->id, 'mail_from_name', $validated['mail_from_name'] ?? $business->name);

        if (!empty($validated['mail_password'])) {
            BusinessSetting::setValue($business->id, 'mail_password', Crypt::encryptString($validated['mail_password']));
        }

        return back()->with('success', 'Correo Gmail configurado correctamente.');
    }

    public function testMail(Request $request)
    {
        $business = auth()->user()->business;
        $settings = BusinessSetting::getMany($business->id, ['mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_name']);

        if (empty($settings['mail_username']) || empty($settings['mail_password'])) {
            return back()->with('error', 'Configure primero el correo y la contraseña.');
        }

        try {
            $password = Crypt::decryptString($settings['mail_password']);

            config([
                'mail.mailers.business' => [
                    'transport' => 'smtp',
                    'host' => $settings['mail_host'],
                    'port' => (int) $settings['mail_port'],
                    'username' => $settings['mail_username'],
                    'password' => $password,
                    'encryption' => $settings['mail_encryption'] === 'none' ? null : $settings['mail_encryption'],
                ],
                'mail.from.address' => $settings['mail_username'],
                'mail.from.name' => $settings['mail_from_name'] ?? $business->name,
            ]);

            \Illuminate\Support\Facades\Mail::mailer('business')
                ->raw('Esta es una prueba de configuración de correo desde ' . $business->name . '. Si recibes este mensaje, la configuración es correcta.', function ($message) use ($settings) {
                    $message->to($settings['mail_username'])
                            ->subject('Prueba de correo - Sistema Comercial Pro');
                });

            return back()->with('success', 'Correo de prueba enviado a ' . $settings['mail_username']);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar correo: ' . $e->getMessage());
        }
    }
}
