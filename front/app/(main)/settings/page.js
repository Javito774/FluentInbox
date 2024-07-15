import styles from "./page.module.css";

export default async function Settings() {
  return (
    <div className={styles.settingsWrapper}>
      <nav>
        <ul>
          <li>Cuenta</li>
          <li>Correo</li>
          <li>Usuarios</li>
        </ul>
      </nav>
      <div className={styles.formWrapper}>
        <h2>Personal Information</h2>
        <p className={styles.disclimer}>
          Use a permanent address where you can receive mail.
        </p>
        <form>
          <div className={styles.col2}>
            <div className={styles.formItem}>
              <label>Nombre</label>
              <input type="text" />
            </div>
            <div className={styles.formItem}>
              <label>Apellidos</label>
              <input type="text" />
            </div>
          </div>
          <div className={styles.formItem}>
            <label>Email</label>
            <input type="email" />
          </div>
          <div className={styles.formItem}>
            <input type="submit" value="Guardar" />
          </div>
        </form>
      </div>
    </div>
  );
}
