def read_file(file_path):
    try:
        with open(file_path, 'r', encoding='utf-8') as file:
            content = file.read()
            print("File read successfully. No encoding issues detected.")
    except UnicodeDecodeError as e:
        print(f"Encoding issue detected: {e}")
        print("Attempting to locate the problem...")
        with open(file_path, 'rb') as file:
            bytes_content = file.read()
        try:
            problematic_part = bytes_content[e.start:e.end]
            print(f"Problematic byte sequence: {problematic_part}")
            print(f"Occurs at byte position: {e.start}")
        except Exception as e:
            print(f"An error occurred while locating the problem: {e}")

if __name__ == "__main__":
    file_path = 'bord_test.php'  # Replace with your actual file path
    read_file(file_path)
