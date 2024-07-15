"use client";
import { setCookie } from "../cookies";
import styles from "./page.module.css";

export default function LoginForm() {
  async function handleSubmit(e) {
    e.preventDefault();
    const email = e.target.email.value;
    const password = e.target.password.value;

    var myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");

    var raw = JSON.stringify({
      identifier: email,
      password: password,
    });

    var requestOptions = {
      method: "POST",
      headers: myHeaders,
      body: raw,
      redirect: "follow",
    };

    try {
      const res = await fetch(
        "http://localhost:8080/auth/login",
        requestOptions,
      );

      if (!res.ok) {
        // Si el código de estado no es exitoso (por ejemplo, 400 o 404), maneja el error.
        const response = await res.json();
        throw new Error(
          `HTTP error! Status: ${res.status}\nMessage: ${JSON.stringify(
            response,
          )}`,
        );
      }

      const resJson = await res.json();
      setCookie("sessionToken", resJson.jwt, 15);
      window.location = "/mails/inbox";
    } catch (error) {
      console.log("error", error);
    }
  }
  return (
    <form onSubmit={handleSubmit}>
      <div>
        <label htmlFor="email">Correo electrónico</label>
        <div className={styles.inputWrapper}>
          <input
            id="email"
            name="email"
            type="email"
            autoComplete="email"
            required=""
          />
        </div>
      </div>

      <div className={styles.inputWrapperMargin}>
        <div className={styles.forgotTextWrapper}>
          <label htmlFor="password">Contraseña</label>
          <div className={styles.forgotText}>
            <a href="#">He olvidado mi contraseña</a>
          </div>
        </div>
        <div className={styles.inputWrapper}>
          <input
            id="password"
            name="password"
            type="password"
            autoComplete="current-password"
            required=""
          />
        </div>
      </div>

      <div className={styles.inputWrapperMargin}>
        <button type="submit" className={styles.submitButton}>
          Iniciar sesión
        </button>
      </div>
    </form>
  );
}
