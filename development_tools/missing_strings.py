# this script will find all missing strings in all PHP files in the specified directory

import os
import re

# Specify the directory
root_directory = "../."

# Specify the second parameter of the function get_string. e.g. "block_exaquest"
second_parameter = "block_exaquest"

# Define the language file path
lang_file_path = os.path.join(root_directory, "lang", "de", "block_exaquest.php")

# Extract existing strings from the language file
existing_strings = set()
if os.path.exists(lang_file_path):
    with open(lang_file_path, 'r', encoding='utf-8') as f:
        content = f.read()
        existing_strings = set(re.findall(r"\$string\['(.*?)'\]", content))
        # existing_strings = set(re.findall(r"\$string\['(.*?)'\], \{\{#str\}\}(.+?), block_exaquest \{\{/str\}\}", content))

# Find PHP files in all subdirectories
added_strings = set()
for dirpath, dirnames, filenames in os.walk(root_directory):
    for filename in filenames:
        # if filename.endswith('.mustache'):
        if filename.endswith('.php') or filename.endswith('.mustache') or filename.endswith('.html') or filename.endswith('.js'):
            file_path = os.path.join(dirpath, filename)
            with open(file_path, 'r', encoding='latin-1') as f: # latin 1 to not have problems with certain symbols
                content = f.read()
            regex_pattern = r"get_string\('([^']*)',\s*'{}'".format(second_parameter)
            matches = re.findall(regex_pattern, content)
            with open(lang_file_path, 'a', encoding='latin-1') as f:
                for match in matches:
                    if match not in existing_strings and match not in added_strings:
                        lang_string = "$string['{}'] = 'TODO: create {}';\n".format(match, match)
                        f.write(lang_string)
                        added_strings.add(match)
                        print(f"Added string for '{match}' in {lang_file_path}")


            regex_pattern = r"{{{{#str}}}}\s*(.+?),\s*{} {{{{/str}}}}".format(second_parameter)

            matches = re.findall(regex_pattern, content)
            with open(lang_file_path, 'a', encoding='utf-8') as f:
                for match in matches:
                    if match not in existing_strings and match not in added_strings:
                        lang_string = "$string['{}'] = 'TODO: create {}';\n".format(match, match)
                        f.write(lang_string)
                        added_strings.add(match)
                        print(f"Added string for '{match}' in {lang_file_path}")
