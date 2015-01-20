<?php namespace ItxTechnologies\ArtisanResxToLang;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ArtisanResxToLang extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'resxToLang';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Converts .NET Resource files (Resx) to Language Files.';

	public function __construct ()
	{
	    parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// First let's find how many languages are in the app

		$langs = glob('app/lang/*' , GLOB_ONLYDIR);

		// Remove the app/lang from the path

		for ($i=0; $i < count($langs); $i++) { 
			$langs[$i] = str_replace('app/lang/', '', $langs[$i]);
		}

		// Load the default language for the app to know which files are what languages

		$defaultLang = Config::get('app.fallback_locale');

		// and remove if from the db

		$index = array_search($defaultLang, $langs);
		if($index !== FALSE){
		    unset($langs[$index]);
		}

		// Get the file name
		
		$filename = $this->argument('name');


		// Check for dumb people not reading the manual

		$index = strpos($filename, '.resx');
		if($index !== FALSE){
			str_replace('.resx', '', $filename);
		}

		// Define an line break and tab
		$enter = "\r\n";
		$tab = "\t";

		// Let's loop through every locales

		for ($i=0; $i < count($langs) + 1; $i++) { 

			// Define the current lang
			$currentLang = $i == 0 ? $defaultLang : $langs[$i-1];

			// Let's start the content of our file

			$file_content = "<?php" . $enter . $enter . $tab . "return array(" . $enter . $enter . $tab;

			// then let's create the filename if it's not the default locale

			$currentFilename = $filename . ($i == 0 ? '' : '.' . $currentLang);

			// and load that baby

			$resx = simplexml_load_file("public/resx/" . $currentFilename . '.resx' );

			// Let's tell everyone that we're still alive before continuing

			echo "File For Locale $currentLang Loaded, Parsing Data";

			// loop all the <data> nodes

			foreach ($resx->data as $node) {
				// create the key value array from the name attribute and the value
				$file_content .= $tab . '"' . $node->attributes()->name . '" => "' . htmlentities(str_replace('"', '\"', $node->value)) . '",' . $enter . $tab;

				// let's not make them wait

				echo '.';
			}

			// Let's close the curtain, this one's finished

			$file_content .= ");";

			// and then save him
			
			file_put_contents('app/lang/' . $currentLang . '/' . strtolower($filename) . '.php', $file_content);

			// and make sure no one worry

			echo "\r\nFile Saved\r\n";

		}

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'Name of the resx file in public/resx without the extention'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
