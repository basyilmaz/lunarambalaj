# Image Optimization Script
# Optimizes PNG files to reduce file size

$imagePath = "C:\YazilimProjeler\lunarambalaj\public\images"

Write-Host "=== Image Optimization Starting ===" -ForegroundColor Green

# List of large PNG files to optimize
$largeFiles = @(
    "product-cup-colorful.png",
    "product-cup-white.png",
    "product-kraft-bag.png",
    "lifestyle-drinks.png"
)

foreach ($file in $largeFiles) {
    $fullPath = Join-Path $imagePath $file

    if (Test-Path $fullPath) {
        $beforeSize = (Get-Item $fullPath).Length / 1MB
        Write-Host "`nProcessing: $file" -ForegroundColor Yellow
        Write-Host "Before: $([math]::Round($beforeSize, 2)) MB" -ForegroundColor Gray

        # Create optimized version using .NET Image class
        Add-Type -AssemblyName System.Drawing

        $img = [System.Drawing.Image]::FromFile($fullPath)
        $newWidth = [Math]::Min($img.Width, 1200)  # Max width 1200px
        $newHeight = [int]($img.Height * ($newWidth / $img.Width))

        $newImg = New-Object System.Drawing.Bitmap($newWidth, $newHeight)
        $graphics = [System.Drawing.Graphics]::FromImage($newImg)
        $graphics.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
        $graphics.DrawImage($img, 0, 0, $newWidth, $newHeight)

        # Save with optimization
        $encoder = [System.Drawing.Imaging.ImageCodecInfo]::GetImageEncoders() | Where-Object { $_.MimeType -eq 'image/png' }
        $encoderParams = New-Object System.Drawing.Imaging.EncoderParameters(1)
        $encoderParams.Param[0] = New-Object System.Drawing.Imaging.EncoderParameter([System.Drawing.Imaging.Encoder]::Quality, 85)

        # Backup original
        $backupPath = $fullPath + ".backup"
        Copy-Item $fullPath $backupPath -Force

        # Save optimized
        $newImg.Save($fullPath, $encoder, $encoderParams)

        $graphics.Dispose()
        $newImg.Dispose()
        $img.Dispose()

        $afterSize = (Get-Item $fullPath).Length / 1MB
        $saved = $beforeSize - $afterSize
        $percent = [math]::Round(($saved / $beforeSize) * 100, 1)

        Write-Host "After: $([math]::Round($afterSize, 2)) MB" -ForegroundColor Green
        Write-Host "Saved: $([math]::Round($saved, 2)) MB ($percent%)" -ForegroundColor Cyan
    }
}

Write-Host "`n=== Optimization Complete ===" -ForegroundColor Green
