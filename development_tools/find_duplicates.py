import re

# get all files in the lang directory
import os

file_paths = []
for dirpath, dirnames, filenames in os.walk('../lang'):
    for filename in filenames:
        if filename.endswith('.php'):
            file_paths.append(os.path.join(dirpath, filename))

# replace backslashes with forward slashes
file_paths = [file_path.replace("\\", "/") for file_path in file_paths]

# remove the total.php file if it exists
if '../lang/total.php' in file_paths:
    file_paths.remove('../lang/total.php')


# Read the contents of the file
# file_path = '../lang/en/block_exaquest.php'

# Function to find duplicate entries
def find_duplicates(content):
    strings = {}
    duplicates = {}
    for i, line in enumerate(content):
        match = re.search(r"\$string\['(.*?)'\]", line)
        if match:
            string_key = match.group(1)
            if string_key in strings:
                if string_key not in duplicates:
                    duplicates[string_key] = [strings[string_key]]
                duplicates[string_key].append(i)
            else:
                strings[string_key] = i
    return duplicates


for file_path in file_paths:
    # Read the content of the file
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.readlines()

    # Find duplicates
    duplicate_entries = find_duplicates(content)

    # Output duplicate entries
    for key, value in duplicate_entries.items():
        for i, v in enumerate(value):
            value[i] += 1  # add 1 to the line number to make it human-readable
        print(f"Duplicate entries for $string['{key}'] found at lines: {value} in file {file_path}")
