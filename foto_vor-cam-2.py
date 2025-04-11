import cv2
import os
import time

# Eine kurze Verzögerung vor dem Start
print("Programm startet in 10 Sekunden...")
time.sleep(10)

# Webcam starten
cap = cv2.VideoCapture(0, cv2.CAP_DSHOW)

# Gesichtserkennungsmodell laden
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')

# Speicherort für Bilder
save_path = r"C:\Users\Student\OneDrive - GFN GmbH (EDU)\Fotos"

# Falls der Ordner nicht existiert, erstelle ihn
if not os.path.exists(save_path):
    os.makedirs(save_path)

print("Überwachung gestartet. Drücke STRG+C zum Beenden.")

# Letztes Bild für Bewegungserkennung
ret, last_frame = cap.read()
last_gray = cv2.cvtColor(last_frame, cv2.COLOR_BGR2GRAY)
last_gray = cv2.GaussianBlur(last_gray, (21, 21), 0)

while True:
    try:
        # Neues Bild von der Webcam holen
        ret, frame = cap.read()
        if not ret:
            break

        # In Graustufen umwandeln und glätten
        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
        gray = cv2.GaussianBlur(gray, (21, 21), 0)

        # Bewegungserkennung: Unterschied zwischen aktuellem und letztem Bild
        frame_diff = cv2.absdiff(last_gray, gray)
        thresh = cv2.threshold(frame_diff, 25, 255, cv2.THRESH_BINARY)[1]
        thresh = cv2.dilate(thresh, None, iterations=2)

        # Konturen finden
        contours, _ = cv2.findContours(thresh, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

        # Bewegung erkannt, wenn eine Kontur größer als 5000 Pixel ist (anpassbar)
        motion_detected = any(cv2.contourArea(c) > 5000 for c in contours)

        # Gesichter erkennen
        faces = face_cascade.detectMultiScale(gray, scaleFactor=1.3, minNeighbors=5, minSize=(30, 30))

        if len(faces) > 0 or motion_detected:
            # Aktuelles Datum und Uhrzeit für Dateinamen
            timestamp = time.strftime("%Y-%m-%d_%H-%M-%S")
            img_name = os.path.join(save_path, f"intruder_{timestamp}.jpg")

            # Foto speichern
            cv2.imwrite(img_name, frame)
            print(f"📸 Foto gespeichert als {img_name} (Grund: {'Gesicht' if len(faces) > 0 else 'Bewegung'})")

        # Das aktuelle Bild als letztes Bild für die nächste Bewegungserkennung speichern
        last_gray = gray

        # Warte 5 Sekunden vor dem nächsten Versuch
        time.sleep(5)

    except KeyboardInterrupt:
        print("\n🚪 Überwachung gestoppt. Programm beendet.")
        break

# Ressourcen freigeben
cap.release()
cv2.destroyAllWindows()

