<?php
/**
 * Assignment One: Simple PHP CLI tool
 */

// Tool Version
define( 'ALPHACOUNT_VERSION', '1.0.0' );

/**
 * Check if user pass any arguments
 */
if ( $argc < 2 ) {
    echo "Usage: php alphacount.php \"<sentence>\"\nSee php alphacount.php --help to read about tools";
    exit( 1 );
}

/**
 * Display Help Text
 *
 * @return void
 */
function displayHelp() {
    echo "alphacount.php - Count alphabetic characters in a sentence\n";
    echo "Usage: php alphacount.php [options] \"<sentence>\"\n";
    echo "Options:\n";
    echo "  -v, --version    Display the version of the CLI tool\n";
    echo "  -h, --help       Display this help text\n";
}

/**
 * Count Given Sentence
 *
 * @param string $sentence
 *
 * @return int
 */
function countAlphabet( $sentence ) {
    $cleanedSentence = preg_replace( "/[^a-zA-Z]/", "", $sentence );
    $count           = strlen( $cleanedSentence );

    return $count;
}

// Get option & value
$options = getopt( 'vh', ["help", "version"] );

/**
 * Check if the --help or -h option is provided
 */
if ( isset( $options['h'] ) || isset( $options['help'] ) ) {
    displayHelp();
    exit( 0 );
}

/**
 * Check if the --version or -v option is provided
 */
if ( isset( $options['v'] ) || isset( $options['version'] ) ) {
    echo "version: " . ALPHACOUNT_VERSION . "\n";
    exit( 0 );
}

// Get given sentence form $argv
$sentence = $argv[1];

// Print Total Alphabet
$count = countAlphabet( $sentence );
echo "$count\n";