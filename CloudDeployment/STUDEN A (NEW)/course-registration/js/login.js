function togglePassword() {

    const password =
        document.getElementById("password");

    if(password.type === "password") {

        password.type = "text";

    } else {

        password.type = "password";
    }
}

function setLanguage(language) {

    const translations = {

        vi: {

            welcome:
            "XIN CHÀO!",

            subtitle:
            "Vui lòng đăng nhập vào hệ thống",

            mssv:
            "Mã số sinh viên (MSSV)",

            password:
            "Mật khẩu",

            login:
            "ĐĂNG NHẬP",

            note:
            "Sử dụng tài khoản sinh viên của bạn để đăng nhập.",

            left1:
            "CỔNG",

            left2:
            "THÔNG TIN",

            left3:
            "SINH VIÊN",

            footer:
            "HỆ THỐNG THÔNG TIN SINH VIÊN"
        },

        en: {

            welcome:
            "WELCOME!",

            subtitle:
            "Please sign in to the system",

            mssv:
            "Student ID",

            password:
            "Password",

            login:
            "SIGN IN",

            note:
            "Use your student account to sign in.",

            left1:
            "STUDENT",

            left2:
            "PORTAL",

            left3:
            "",

            footer:
            "STUDENT INFORMATION SYSTEM"
        }
    };

    const text =
        translations[language];

    document.getElementById(
        "welcome-title"
    ).textContent =
        text.welcome;

    document.getElementById(
        "login-subtitle"
    ).textContent =
        text.subtitle;

    document.getElementById(
        "mssv"
    ).placeholder =
        text.mssv;

    document.getElementById(
        "password"
    ).placeholder =
        text.password;

    document.getElementById(
        "login-button"
    ).textContent =
        text.login;

    document.getElementById(
        "login-note"
    ).textContent =
        text.note;

    document.getElementById(
        "left-title-1"
    ).textContent =
        text.left1;

    document.getElementById(
        "left-title-2"
    ).textContent =
        text.left2;

    document.getElementById(
        "left-title-3"
    ).textContent =
        text.left3;

    document.getElementById(
        "left-footer"
    ).textContent =
        text.footer;

    document.documentElement.lang =
        language;
}