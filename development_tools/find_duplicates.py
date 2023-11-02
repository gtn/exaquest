import re

# Read the contents of the file
file_path = '../lang/en/block_exaquest.php'

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

# Read the content of the file
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.readlines()

# Find duplicates
duplicate_entries = find_duplicates(content)

# Output duplicate entries
for key, value in duplicate_entries.items():
    print(f"Duplicate entries for $string['{key}'] found at lines: {value}")
