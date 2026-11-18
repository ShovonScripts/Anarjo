from playwright.sync_api import sync_playwright
import json

def test_website():
    issues = []
    
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()
        
        # Enable console logging
        page.on("console", lambda msg: print(f"CONSOLE [{msg.type}]: {msg.text}"))
        page.on("pageerror", lambda err: print(f"PAGE ERROR: {err}"))
        
        # Test homepage
        print("\n=== Testing Homepage ===")
        page.goto("http://localhost:8000", wait_until="networkidle")
        page.wait_for_load_state("networkidle")
        page.screenshot(path="/tmp/homepage.png", full_page=True)
        
        # Check for images
        images = page.locator("img").all()
        print(f"Found {len(images)} images on homepage")
        for i, img in enumerate(images):
            src = img.get_attribute("src")
            alt = img.get_attribute("alt")
            visible = img.is_visible()
            print(f"  Image {i}: src={src}, alt={alt}, visible={visible}")
            if src and not visible:
                issues.append(f"Image not visible: {src}")
        
        # Check for logo
        logo = page.locator("img[alt*='Anarjo'], img[alt*='logo' i], img[class*='logo' i]").first
        if logo.count() > 0:
            print(f"Logo found: {logo.get_attribute('src')}")
        else:
            issues.append("No logo found on homepage")
        
        # Test login page
        print("\n=== Testing Login Page ===")
        page.goto("http://localhost:8000/login", wait_until="networkidle")
        page.wait_for_load_state("networkidle")
        page.screenshot(path="/tmp/login_page.png", full_page=True)
        
        # Try login with correct selectors (Livewire form)
        page.fill('#email', 'admin@anarjo.com')
        page.fill('#password', 'password')
        page.click('button[type="submit"]')
        page.wait_for_load_state("networkidle")
        
        # Check if logged in (should redirect to admin dashboard)
        if "/admin" in page.url or "/dashboard" in page.url:
            print("Admin login successful!")
        else:
            issues.append(f"Admin login failed - current URL: {page.url}")
            page.screenshot(path="/tmp/admin_login_failed.png", full_page=True)
        
        # Test admin panel
        print("\n=== Testing Admin Panel ===")
        page.goto("http://localhost:8000/admin", wait_until="networkidle")
        page.wait_for_load_state("networkidle")
        page.screenshot(path="/tmp/admin_panel.png", full_page=True)
        
        # Check for navigation
        nav_items = page.locator("nav a, .sidebar a, [role='navigation'] a").all()
        print(f"Found {len(nav_items)} navigation items in admin")
        
        # Test some key pages
        test_pages = [
            ("/books", "Books page"),
            ("/authors", "Authors page"),
            ("/blog", "Blog page"),
            ("/events", "Events page"),
            ("/contact", "Contact page"),
        ]
        
        for url, name in test_pages:
            print(f"\n=== Testing {name} ===")
            page.goto(f"http://localhost:8000{url}", wait_until="networkidle")
            page.wait_for_load_state("networkidle")
            page.screenshot(path=f"/tmp/{name.replace(' ', '_').lower()}.png", full_page=True)
            
            # Check for 500 errors
            if "500" in page.title() or "Whoops" in page.content():
                issues.append(f"{name} shows error page")
            
            # Check for content
            content = page.content()
            if len(content) < 1000:
                issues.append(f"{name} seems empty or has minimal content")
        
        browser.close()
    
    # Summary
    print("\n=== SUMMARY ===")
    if issues:
        print(f"Found {len(issues)} issues:")
        for issue in issues:
            print(f"  - {issue}")
    else:
        print("No issues found!")
    
    return issues

if __name__ == "__main__":
    issues = test_website()
    with open("/tmp/test_results.json", "w") as f:
        json.dump({"issues": issues, "count": len(issues)}, f)
    exit(1 if issues else 0)