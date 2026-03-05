from PIL import Image
import collections

def get_dominant_colors(image_path, num_colors=5):
    try:
        image = Image.open(image_path)
        image = image.convert('RGB')
        image = image.resize((150, 150))
        result = image.getcolors(150 * 150)
        sorted_colors = sorted(result, key=lambda t: t[0], reverse=True)
        return [c[1] for c in sorted_colors[:num_colors]]
    except Exception as e:
        print(f"Error: {e}")
        return []

image_path = "C:/Users/yimma/.gemini/antigravity/brain/6e73028d-6c61-435e-b465-8390fa213dac/media__1770905935343.png"
colors = get_dominant_colors(image_path)
print("Dominant Colors (RGB):")
for color in colors:
    print(f"RGB{color} - Hex: #{color[0]:02x}{color[1]:02x}{color[2]:02x}")
