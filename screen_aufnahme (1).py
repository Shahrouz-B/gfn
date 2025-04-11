import cv2
import numpy as np
import pyautogui
import time
import os
import keyboard
from datetime import datetime, timedelta

# Sicherstellen, dass der Zielordner existiert
output_dir = "Aufnahmen"
if not os.path.exists(output_dir):
    os.makedirs(output_dir)


# Funktion, um einen eindeutigen Dateinamen zu generieren
def generate_filename():
    timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
    return os.path.join(output_dir, f"screen_recording_{timestamp}.avi")


# Pfad zur Ausgabedatei
output_path = generate_filename()

# Bildschirmgröße ermitteln
screen_size = pyautogui.size()

# Codec und VideoWriter-Objekt erstellen
fourcc = cv2.VideoWriter_fourcc(*"XVID")
out = cv2.VideoWriter(output_path, fourcc, 20.0, (screen_size.width, screen_size.height))

print("Drücke 'Ctrl + Shift + Q', um die Aufnahme zu beenden.")
print(f"Die Aufnahme wird gespeichert unter: {output_path}")

# Endzeit nach 2 Stunden berechnen
end_time = datetime.now() + timedelta(hours=2)

while True:
    try:
        # Screenshot aufnehmen
        img = pyautogui.screenshot()

        # Screenshot in ein numpy-Array konvertieren
        frame = np.array(img)

        # Farben von RGB zu BGR umwandeln
        frame = cv2.cvtColor(frame, cv2.COLOR_RGB2BGR)

        # Frame schreiben
        out.write(frame)

        # Frame anzeigen (optional)
        # cv2.imshow("Recording", frame)

        # Überprüfen, ob die Tastenkombination gedrückt wurde
        if keyboard.is_pressed('ctrl+shift+q'):
            print("Aufnahme beendet durch Tastenkombination.")
            break

        # Überprüfen, ob die Endzeit erreicht wurde
        if datetime.now() >= end_time:
            print("Aufnahme automatisch nach 2 Stunden beendet.")
            break

    except KeyboardInterrupt:
        print("Aufnahme durch KeyboardInterrupt beendet.")
        break

# Alles freigeben, wenn die Arbeit erledigt ist
out.release()
cv2.destroyAllWindows()
