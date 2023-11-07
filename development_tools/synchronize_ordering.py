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

if len(en_dict) != len(de_keys):
    print("ERROR: NUMBER OF STRINGS IN ENGLISH FILE IS NOT EQUAL TO NUMBER OF STRINGS IN GERMAN FILE")
    print(f"ENGLISH: {len(en_dict)}")
    print(f"GERMAN: {len(de_keys)}")
    print("RUN lang_compare.py FIRST!")
    exit()

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
                # if it does NOT exist in the english file, then it is a new string ==> write it in german but also add TRANSLATE to it
                # match = re.match(r"\$string\['(.*?)'\]\s*=\s*'(.*)';", line)
                # new_line = f"$string['{match.group(1)}'] = '{match.group(2)} TRANSLATE';\n"
                # en_file.write(new_line)
                # en_file.write(line)
                print(f"ERROR: STRING DOES NOT EXIST IN ENGLISH FILE: {key} \n RUN lang_compare.py FIRST!") # should not happen
        else:
            if not (line_stripped.startswith("\'") or line_stripped.startswith("\"")):
                en_file.write(line)
                # if it DOES start with " or with ' then it is a second line of a string, because it is a long string ==> skip, or else it would write the german string

