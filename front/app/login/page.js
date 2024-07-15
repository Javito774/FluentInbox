import LoginForm from "./loginForm";
import styles from "./page.module.css";

export default function Login() {
  return (
    <div className={styles.pageWrapper}>
      <div className={styles.loginWrapper}>
        <div className={styles.brandingWrapper}>
          <img className={styles.logo} src="/Logo.svg" alt="Your Company" />
          <h2 className={styles.title}>Inicia sesión en tu cuenta</h2>
        </div>
        <div className={styles.formWrapper}>
          <LoginForm />
        </div>
      </div>
    </div>
  );
}
