# development_tools
tools for developing moodle extensions. e.g. synchronising lang files

# How to run:
Put the development_tools folder into the folder of a plugin (at the same depth as the lang folder)

python filename_of_the_script.py

Before running it: simply search and replace every e.g. "exaquest" with e.g. "exaport", depending on which plugin you want to use the scripts in.

# Scripts:
## find_duplicates: 
Finds duplicates in the file. Which file: All files in the lang folder.

## lang_compare:
Compares the de and en files and writes the missing strings from de to en as well as from en to de. 

## missing_strings: 
Looks for get_string() in php files and {{str}} in mustache files and adds the missing strings to lang file. Which file depends on the path again, e.g. lang_file_path = os.path.join(root_directory, "lang", "de", "block_exaquest.php") for the de file.

## synchronize_ordering:
Orders the en file the same way the de file is ordered. CAREFUL: It simply writes everything from the de file and replaces only the strings with the ones taken from the en file. If you have comments in the en file, but not in the de file ==> they will get deleted.
