def split_long_lines(file_path, max_length=100):
    with open(file_path, 'r', encoding='utf-8') as file:
        lines = file.readlines()

    new_lines = []
    for line in lines:
        while len(line) > max_length:
            # Find the last space character before the max_length
            split_index = line.rfind(' ', 0, max_length)
            if split_index == -1:  # No space found, force split at max_length
                split_index = max_length
            new_lines.append(line[:split_index] + '\n')
            line = line[split_index:]
        new_lines.append(line)

    with open(file_path, 'w', encoding='utf-8') as file:
        file.writelines(new_lines)

# Replace 'path_to_your_file.php' with the actual file path
file_path = 'bord_test.php'
split_long_lines(file_path)
