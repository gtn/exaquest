# run this script to synchronize the language files.
# Whenever there is a string in one file but not the other, it will be added with a "TODO TRANSLATE" comment.

import re

# Read the contents of the files
de_file = '../lang/de/block_exaquest.php'
en_file = '../lang/en/block_exaquest.php'

# Function to extract key-value pairs from content
def extract_strings(content):
    strings = {}
    for i, line in enumerate(content):
        match = re.search(r"\$string\['(.*?)'\]\s*=\s*'(.*)';", line)
        if match:
            strings[match.group(1)] = {'line': line, 'index': i}
    return strings

# Read the contents of the files
with open(de_file, 'r', encoding='utf-8') as f:
    de_content = f.readlines()

with open(en_file, 'r', encoding='utf-8') as f:
    en_content = f.readlines()

# Extracting the key-value pairs from the content
de_strings = extract_strings(de_content)
en_strings = extract_strings(en_content)

# Synchronize from English to German
for key, value in en_strings.items():
    if key not in de_strings:
        match = re.match(r"\$string\['(.*?)'\] = '(.*)';", value['line'])
        if match:
            new_line = f"$string['{match.group(1)}'] = '{match.group(2)} TODO TRANSLATE';\n"
            de_content.insert(value['index'], new_line)

# Synchronize from German to English
for key, value in de_strings.items():
    if key not in en_strings:
        match = re.match(r"\$string\['(.*?)'\] = '(.*)';", value['line'])
        if match:
            new_line = f"$string['{match.group(1)}'] = '{match.group(2)} TODO TRANSLATE';\n"
            en_content.insert(value['index'], new_line)

# Update the files with the synchronized strings
with open(de_file, 'w', encoding='utf-8') as f:
    f.writelines(de_content)

with open(en_file, 'w', encoding='utf-8') as f:
    f.writelines(en_content)
