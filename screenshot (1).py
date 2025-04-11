from PIL import ImageGrab
import pyautogui
import time
from pynput import keyboard
import threading

# Define variables for screenshot and filename
path = r"C:\Users\Student\Desktop\Aks\\"  # Use raw string for the path

# Variable to track if a screenshot is currently being captured
screenshot_in_progress = False

# Function to capture screenshot and save it
def capture_and_save(image):
    global path

    # Generate unique filename with timestamp
    timestamp = time.strftime("%H.%M.%S")  # Format time as std.min.sek
    filename = f"{timestamp}.png"  # Use PNG format for better quality

    # Save the screenshot in PNG format (lossless quality)
    image.save(path + filename, format='PNG')

    # Print confirmation message
    print(f"Screenshot saved as {filename} in {path}")

# Function to handle key press events
def on_press(key):
    global screenshot_in_progress
    if key == keyboard.Key.print_screen:
        if not screenshot_in_progress:
            screenshot_in_progress = True
            screenshot = pyautogui.screenshot()
            capture_and_save(screenshot)
            time.sleep(0)  # Optional delay to prevent rapid consecutive captures
            screenshot_in_progress = False

# Function to monitor clipboard for image content
def monitor_clipboard():
    recent_value = None
    while True:
        try:
            image = ImageGrab.grabclipboard()
            if image is not None and image != recent_value:
                recent_value = image
                capture_and_save(image)
        except Exception as e:
            print(f"Error capturing clipboard image: {e}")
        time.sleep(0)

# Create a keyboard listener to detect the "Print Screen" key press
def listen_for_print_screen():
    with keyboard.Listener(on_press=on_press) as listener:
        listener.join()

# Start the keyboard listener and clipboard monitor in separate threads
if __name__ == "__main__":
    # Start clipboard monitor thread
    clipboard_thread = threading.Thread(target=monitor_clipboard, daemon=True)
    clipboard_thread.start()

    # Start the keyboard listener
    listen_for_print_screen()
