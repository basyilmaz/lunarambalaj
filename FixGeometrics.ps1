Add-Type -AssemblyName System.Drawing

function CreateGeometricImage($dest, $bgColor1, $bgColor2) {
    $bmp = New-Object System.Drawing.Bitmap(800, 600)
    $gfx = [System.Drawing.Graphics]::FromImage($bmp)
    $gfx.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::AntiAlias
    
    $rect = New-Object System.Drawing.Rectangle(0, 0, 800, 600)
    $color1 = [System.Drawing.Color]::FromName($bgColor1)
    $color2 = [System.Drawing.Color]::FromName($bgColor2)
    $brushBg = New-Object System.Drawing.Drawing2D.LinearGradientBrush($rect, $color1, $color2, [System.Drawing.Drawing2D.LinearGradientMode]::ForwardDiagonal)
    $gfx.FillRectangle($brushBg, $rect)
    
    # Draw some elegant geometric shapes for abstract placeholder
    $brushShade = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::FromArgb(30, 255, 255, 255))
    $brushDark = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::FromArgb(20, 0, 0, 0))
    $pen = New-Object System.Drawing.Pen([System.Drawing.Color]::FromArgb(50, 255, 255, 255), 4)

    # Circles
    $gfx.FillEllipse($brushShade, -100, -100, 400, 400)
    $gfx.FillEllipse($brushDark, 600, 400, 300, 300)
    
    # Lines
    $gfx.DrawLine($pen, 0, 300, 800, 100)
    $gfx.DrawLine($pen, 100, 600, 700, 0)

    # Rectangles (tilted)
    $gfx.TranslateTransform(400, 300)
    $gfx.RotateTransform(45)
    $gfx.DrawRectangle($pen, -150, -150, 300, 300)
    $gfx.ResetTransform()

    $bmp.Save($dest, [System.Drawing.Imaging.ImageFormat]::Jpeg)
    
    $brushBg.Dispose()
    $brushShade.Dispose()
    $brushDark.Dispose()
    $pen.Dispose()
    $gfx.Dispose()
    $bmp.Dispose()
}

$dir = 'c:\YazilimProjeler\lunarambalaj\public\images'

CreateGeometricImage "$dir\ref-horeca.jpg" "Sienna" "DarkRed"
CreateGeometricImage "$dir\ref-hotel.jpg" "Teal" "DarkSlateGray"
CreateGeometricImage "$dir\ref-retail.jpg" "Indigo" "MidnightBlue"
CreateGeometricImage "$dir\ref-fastfood.jpg" "OrangeRed" "Maroon"
CreateGeometricImage "$dir\ref-catering.jpg" "DarkGoldenrod" "SaddleBrown"
CreateGeometricImage "$dir\ref-wholesale.jpg" "SlateGray" "DarkSlateBlue"

Write-Host "Created textless abstract B2B quality geometric headers for reference sections."
