<?php

function generator(
    string $bakeryName,
    string $modelName,
    string $modelFQCN
): string
{
    $php = '<?php

    ';
    $template = '
namespace App\Fixtures\Bakery;

use ' . $modelFQCN . ';
use Generator;

#[AsBakery(bakes: ' . $modelName . '::class)]
final class ' . $bakeryName . ' extends AbstractBakery
{
    public function build(): Generator
    {
        yield [
            //
            // Let\'s get bakybaky.
            //
            // "firstname" => "ba",
            // "lastname" => "guette",
            //
        ];
    }
}

';

    return $php . $template;
}

function prompt(string $file): ?string
{
    $doubleCheck = readline('Is this the one to bake ? ' . $file . ' (Y/n) :');

    return in_array($doubleCheck, ['y', 'Y', 'yes', 'Yes', 'YES', '']);
}

function checkModelIsSet(string $modelName): void
{
    if ($modelName === '') {
        echo '########## ] ---- Model name missing, aborting. ---- [ ##########' . PHP_EOL . 'Command example: make bakery model=Product' . PHP_EOL;
        exit;
    }
}

function checkModelIsFound(?string $file, string $modelName): void
{
    if ($file === null) {
        echo '########## ] ---- Cannot find class ' . $modelName . ' to bake, aborting... ---- [ ##########' . PHP_EOL;
        exit;
    }
}

function checkUserContinues(string $file): void
{
    if (!prompt($file)) {
        echo '########## ] ---- Aborting... ---- [ ##########' . PHP_EOL;
        exit;
    }
}

function getModelFQCN(string $file): string
{
    $modelFQCN = str_replace('.php', '', $file);
    $modelFQCN = str_replace('src', 'App', $modelFQCN);

    return str_replace('/', "\\", $modelFQCN);
}

function checkBakeryDoesNotExist(string $modelName): void
{
    if (file_exists('src/Bakery/' . $modelName . 'Bakery.php')) {
        echo '########## ] ---- Bakery for "' . $modelName . '" already exist, aborting. ---- [ ##########' . PHP_EOL;
        exit;
    }
}

function bakeBakery(string $modelName, string $modelFQCN): void
{
    if (file_put_contents(
        'src/Bakery/' . $modelName . 'Bakery.php',
        generator($modelName . 'Bakery', $modelName, $modelFQCN)
    )) {
        echo '[   OK   ] - New bakery created here: src/Fixtures/Bakery/' . $modelName . 'Bakery' . PHP_EOL;
    } else {
        echo '[ FAILED ] - Didn\'t work, try again maybe ..?' . PHP_EOL;
        exit;
    }
}

function getFile(string $modelName): ?string
{
    $modelSearch = shell_exec('find src/* | grep ' . $modelName . '.php');

    if ($modelSearch === null || $modelSearch === '') {
        return null;
    }

    if (substr_count($modelSearch, 'src') > 1) {

        $options = preg_split("/\r\n|\n|\r/", $modelSearch);
        $options = array_diff($options, ['']);

        $choices = $options;

        for ($i = 0; $i < count($choices); $i++) {
            $choices[$i] = '[' . $i . '] -> ' . $choices[$i];
        }

        $choicePrompt = '';
        foreach ($choices as $choice) {
            $choicePrompt .= $choice . PHP_EOL;
        }

        echo '########## ] ---- Multiple classes found ---- [ ##########' . PHP_EOL;
        $choose = readline($choicePrompt . ' ([0], 1, ... :');

        if ($choose === '') {
            $choose = 0;
        }

        if (!array_key_exists($choose, $options)) {
            echo '########## ] ---- Not one of the options, try again... ---- [ ##########' . PHP_EOL;
            getFile($modelName);
        }

        return $options[$choose];
    } else {
        return $modelSearch;
    }
}

function createBakery(string $modelName): void
{
    checkModelIsSet($modelName);
    $file = getFile($modelName) ?? null;
    checkModelIsFound($file, $modelName);
    checkUserContinues($file);
    $modelFQCN = getModelFQCN($file);
    checkBakeryDoesNotExist($modelName);
    bakeBakery($modelName, $modelFQCN);
}
