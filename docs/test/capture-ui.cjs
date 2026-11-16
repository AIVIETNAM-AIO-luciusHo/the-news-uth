// Point PLAYWRIGHT_MODULE to a local Playwright installation, or install it outside this repo.
const { chromium } = require(process.env.PLAYWRIGHT_MODULE || 'playwright');
const fs = require('node:fs');
(async () => {
    const browser = await chromium.launch({ headless: true });
    fs.mkdirSync('docs/test/screenshots', { recursive: true });
    for (const width of [375, 768, 1366]) {
        const page = await browser.newPage({ viewport: { width, height: 900 } });
        for (const [name, path, status] of [['home', '/', 200], ['login', '/admin/login', 200], ['404', '/missing', 404]]) {
            const response = await page.goto((process.env.BASE_URL || 'http://127.0.0.1:8080') + path);
            if (response.status() !== status) throw new Error(`${path}: wrong status`);
            const overflow = await page.evaluate(() => document.documentElement.scrollWidth > innerWidth);
            if (overflow) throw new Error(`${path}: overflow at ${width}`);
            await page.screenshot({ path: `docs/test/screenshots/${name}-${width}.png`, fullPage: true });
            console.log(`PASS ${name} ${width}px`);
        }
        await page.close();
    }
    await browser.close();
})().catch(error => { console.error(error); process.exit(1); });
