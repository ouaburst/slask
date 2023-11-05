def find_line_of_byte(file_path, byte_position):
    with open(file_path, 'rb') as file:  # Open the file in binary read mode
        line_number = 1  # Start at the first line
        current_byte_position = 0  # Start at the first byte

        while current_byte_position < byte_position:  # Loop until the specified byte position
            char = file.read(1)  # Read one byte
            if not char:
                # End of file reached without finding the byte position
                raise EOFError("Reached end of file without reaching the specified byte position.")
            current_byte_position += 1
            if char == b'\n':  # If the byte is a newline character, increment the line number
                line_number += 1

        return line_number

# Replace 'path_to_your_file.php' with the actual file path
file_path = 'bord_test.php'
byte_position = 6992  # The byte position we're looking for

try:
    line_number = find_line_of_byte(file_path, byte_position)
    print(f"The issue is around line number: {line_number}")
except EOFError as e:
    print(e)
