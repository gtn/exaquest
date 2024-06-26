# run this script to synchronize the language files.
# Whenever there is a string in one file but not the other, it will be added with a "TODO TRANSLATE" comment.

import re

# Read the contents of the files
de_file = '../lang/de/block_exaquest.php'
en_file = '../lang/en/block_exaquest.php'


def extract_strings(content):
    strings = {}
    for i, line in enumerate(content):
        match = re.search(r"\$string\['(.*?)'\]\s*=\s*(.*);", line)
        if match:
            strings[match.group(1)] = {'line': line, 'index': i}
        else:
            # it is a multiline entry
            match = re.search(r"\$string\['(.*?)'\]\s*=\s*(.*)", line)
            if match:
                while not line.endswith(";"):
                    # continue to add the next line
                    i += 1
                    line += content[i].strip()
                strings[match.group(1)] = {'line': line + "\n", 'index': i}  # add the whole line
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
        match = re.match(r"\$string\['(.*?)'\]\s*=\s*'(.*)';", value['line'])
        if match:
            new_line = f"$string['{match.group(1)}'] = '{match.group(2)} TODO TRANSLATE';\n"
            de_content.insert(value['index'], new_line)

# Synchronize from German to English
for key, value in de_strings.items():
    if key not in en_strings:
        match = re.match(r"\$string\['(.*?)'\]\s*=\s*'(.*)';", value['line'])
        if match:
            new_line = f"$string['{match.group(1)}'] = '{match.group(2)} TODO TRANSLATE';\n"
            en_content.insert(value['index'], new_line)
        # else:
            # # it is a multiline entry
            # match = re.search(r"\$string\['(.*?)'\]\s*=\s*(.*)", value['line'])
            # new_line = f"$string['{match.group(1)}'] = '{match.group(2)} TODO TRANSLATE';\n"
            # if match:
            #     while not value['line'].endswith(";"):
            #         # continue to add the next line
            #         new_line += "asdf"


# Update the files with the synchronized strings
with open(de_file, 'w', encoding='utf-8') as f:
    f.writelines(de_content)

with open(en_file, 'w', encoding='utf-8') as f:
    f.writelines(en_content)
