<?php

namespace Illuminate\Foundation {
    if (!function_exists('Illuminate\Foundation\is_writable')) {
        function is_writable(string $path): bool
        {
            if (is_dir($path)) {
                $test = rtrim($path, '/\\') . '/.write_test_' . uniqid('', true);
                $written = @file_put_contents($test, '1');
                if ($written !== false) {
                    @unlink($test);
                    return true;
                }
            }

            return \is_writable($path);
        }
    }
}

namespace Illuminate\Filesystem {
    if (!function_exists('Illuminate\Filesystem\is_writable')) {
        function is_writable(string $path): bool
        {
            if (is_dir($path)) {
                $test = rtrim($path, '/\\') . '/.write_test_' . uniqid('', true);
                $written = @file_put_contents($test, '1');
                if ($written !== false) {
                    @unlink($test);
                    return true;
                }
            }

            return \is_writable($path);
        }
    }
}

namespace PHPUnit\TextUI\Configuration {
    if (!function_exists('PHPUnit\TextUI\Configuration\is_readable')) {
        function is_readable(string $path): bool
        {
            return file_exists($path);
        }
    }
}
