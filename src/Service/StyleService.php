<?php

namespace App\Service;

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Exception\SassException;
use ScssPhp\ScssPhp\OutputStyle;

readonly class StyleService
{

    public function __construct(
        private ConfigService $configService
    ) {
    }

    public function recompileSass(): bool
    {
        $sourcePath = realpath(__DIR__ . '/../../assets/styles');
        $sourceFile = $sourcePath . '/app.scss';
        $destPath   = realpath(__DIR__ . '/../../public');
        $destFile   = $destPath . '/gintonic.css';

        $sassContent = file_get_contents($sourceFile);


        $newPrimaryColor = $this->configService->getConfigItem('themePrimaryColor');

        if ($newPrimaryColor) {
            // Define the regex pattern to find the line with $primary and capture the entire line
            $pattern = '/(\$primary:\s*)#[0-9A-Fa-f]{6}(\s*;)/';

            // Replace the old hexadecimal value with the new one
            $replacement = '${1}' . $this->configService->getConfigItem('themePrimaryColor') . '${2}';
            $sassContent = preg_replace($pattern, $replacement, $sassContent);
        }

        $compiler = new Compiler();
        $compiler->setImportPaths($sourcePath);
        $compiler->setOutputStyle(OutputStyle::COMPRESSED);

        try {
            $result = $compiler->compileString($sassContent)->getCss();
        } catch (SassException) {
            return false;
        }

        $writeResult = file_put_contents($destFile, $result);

        if (!$writeResult) {
            return false;
        }

        $this->newCacheKey();

        return true;
    }

    public function newCacheKey(): void
    {
        // generate a random 6 character string
        $this->configService->setConfigKey('styleCacheKey', bin2hex(random_bytes(3)));
    }

}
