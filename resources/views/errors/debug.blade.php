<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error - Debug (Super Admin)</title>
    <style>
        body { font-family: monospace; background: #1a1a2e; color: #eee; padding: 20px; }
        .error-box { background: #16213e; border: 1px solid #e94560; border-radius: 8px; padding: 20px; max-width: 900px; margin: 20px auto; }
        h1 { color: #e94560; }
        .message { color: #ffd700; font-size: 1.1em; word-break: break-all; }
        .file { color: #0f3460; background: #e94560; padding: 2px 8px; border-radius: 4px; }
        pre { background: #0f3460; padding: 15px; border-radius: 5px; overflow-x: auto; font-size: 0.85em; line-height: 1.5; }
        .note { color: #888; font-size: 0.9em; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="error-box">
        <h1>🔧 Error Debug (Solo Super Admin)</h1>
        <p class="message">{{ $exception->getMessage() }}</p>
        <p><span class="file">{{ $exception->getFile() }}:{{ $exception->getLine() }}</span></p>
        <h3>Stack Trace:</h3>
        <pre>{{ $exception->getTraceAsString() }}</pre>
        <p class="note">Esta vista solo es visible para super-admins. Los demás usuarios ven "500 Server Error".</p>
    </div>
</body>
</html>
