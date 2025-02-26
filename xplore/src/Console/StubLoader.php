<?php

namespace Xplore\Console;

class StubLoader
{
    public static function loadStub(string $stubPath, array $replacements = []): string
    {
        if (!file_exists($stubPath)) {
            throw new \Exception("Stub file not found: $stubPath");
        }

        $stub = file_get_contents($stubPath);

        foreach ($replacements as $key => $value) {
            $stub = str_replace("{{{$key}}}", $value, $stub);
        }

        return $stub;
    }
}