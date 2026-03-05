from PIL import Image
import collections

def get_dominant_colors(image_path, num_colors=5):
    try:
        image = Image.open(image_path)
        if image.mode in ('RGBA', 'LA'):
            background = Image.new(image.mode[:-1], image.size, (255, 255, 255))
            background.paste(image, image.split()[-1])
            image = background
        
        image = image.convert('RGB')
        image = image.resize((150, 150))
        
        # Get colors
        result = image.getcolors(150 * 150)
        
        # Sort by count
        sorted_colors = sorted(result, key=lambda t: t[0], reverse=True)
        
        # Filter out white/near-white and black/near-black if desired, 
        # but let's just show top 10 to be safe
        return [c[1] for c in sorted_colors[:10]]
    except Exception as e:
        print(f"Error: {e}")
        return []

image_path = "C:/Users/yimma/.gemini/antigravity/brain/6e73028d-6c61-435e-b465-8390fa213dac/media__1770905935343.png"
colors = get_dominant_colors(image_path)
print("Dominant Colors (RGB):")
for color in colors:
    print(f"RGB{color} - Hex: #{color[0]:02x}{color[1]:02x}{color[2]:02x}")
