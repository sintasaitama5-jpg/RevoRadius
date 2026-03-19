import asyncio
from playwright.async_api import async_playwright

async def run():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page()

        print("Membuka halaman login...")
        await page.goto("http://127.0.0.1:8000/login")

        print("Mengisi kredensial...")
        await page.fill("#email", "admin@revo.com")
        await page.fill("#password", "password123")

        print("Klik tombol Masuk...")
        await page.click("button[type='submit']")

        print("Menunggu navigasi ke dashboard...")
        await page.wait_for_url("http://127.0.0.1:8000/dashboard")

        # Biarkan halaman memuat sebentar
        await page.wait_for_timeout(2000)

        # Screenshot sidebar awal
        await page.screenshot(path="/home/jules/verification/sidebar_dashboard.png")
        print("Screenshot sidebar_dashboard.png berhasil disimpan.")

        print("Mencoba klik PPPoE...")
        try:
            await page.click("button:has-text('PPPoE')")
            await page.wait_for_timeout(1000)
            await page.screenshot(path="/home/jules/verification/sidebar_pppoe.png")
            print("Berhasil klik PPPoE.")
        except Exception as e:
            print("Gagal klik PPPoE:", e)

        print("Mencoba klik Hotspot...")
        try:
            await page.click("button:has-text('Hotspot')")
            await page.wait_for_timeout(1000)
            await page.screenshot(path="/home/jules/verification/sidebar_hotspot.png")
            print("Berhasil klik Hotspot.")
        except Exception as e:
            print("Gagal klik Hotspot:", e)

        await browser.close()

asyncio.run(run())
