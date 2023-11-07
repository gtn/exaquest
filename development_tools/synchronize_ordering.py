import re

de_file_path = "../lang/de/block_exaquest.php"
en_file_path = "../lang/en/block_exaquest.php"

# Read German file
with open(de_file_path, 'r') as de_file:
    de_lines = de_file.readlines()

# Extract keys from the German file
de_keys = [re.search(r"\['(.*?)'\]", line).group(1) for line in de_lines if line.strip().startswith("$string")]

# Read English file
with open(en_file_path, 'r') as en_file:
    en_lines = en_file.readlines()

# Create a dictionary of English strings
# en_dict = {}
# current_key = ''
# for line in en_lines:
#     line_stripped = line.strip()
#     if line_stripped.startswith("$string"):
#         current_key = re.search(r"\['(.*?)'\]", line_stripped).group(1)
#     if '=' in line and current_key:
#         en_dict[current_key] = line
#         current_key = ''


# Create a dictionary of English strings
en_dict = {}
current_key = ''
current_value = ''
for line in en_lines:
    line_stripped = line.strip()
    if line_stripped.startswith("$string"):
        if current_key:
            en_dict[current_key] = current_value
            current_value = ''
        current_key = re.search(r"\['(.*?)'\]", line_stripped).group(1)
    if current_key:
        current_value += line
        if ';' in line:
            en_dict[current_key] = current_value
            current_key = ''
            current_value = ''


# Write the ordered English file
with open(en_file_path, 'w') as en_file:
    # iterate over the GERMAN file, write the <php and comments and empty lines from the german file but the strings from the english file
    for line in de_lines:
        line_stripped = line.strip()
        if line_stripped.startswith("$string"):
            key = re.search(r"\['(.*?)'\]", line_stripped).group(1)
            if key in en_dict:
                en_line = en_dict[key]
                en_file.write(en_line)
                del en_dict[key]
            else:
                en_file.write(line)
        else:
            if not (line_stripped.startswith("\'") or line_stripped.startswith("\"")):
                en_file.write(line)
                # if it DOES start with " or with ' then it is a second line of a string, because it is a long string ==> skip, or else it would write the german string



