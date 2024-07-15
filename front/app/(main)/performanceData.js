"use client";
import styles from "./performance.module.css";
import { useEffect, useState } from "react";
import { API } from "./utils";

export default function PerformanceData() {
  const [data, setData] = useState(null);
  useEffect(() => {
    API("/queue/stats").then((data) => {
      setData(data);
    });
  }, []);
  return (
    <div className={styles.statsCard}>
      <div>
        <p className={styles.cardtitle}>Capacidad</p>
        <p className={styles.cardDescription}>
          Correos que se pueden enviar en una hora.
        </p>
        <p className={styles.data}>{data?.limit || ""}</p>
      </div>
      <div>
        <p className={styles.cardtitle}>En cola</p>
        <p className={styles.cardDescription}>
          Correos que estan esperando a ser enviados.
        </p>
        <p className={styles.data}>{data?.queued || ""}</p>
      </div>
      <div>
        <p className={styles.cardtitle}>Ultimos envios</p>
        <p className={styles.cardDescription}>
          Correos enviado en la ultima hora.
        </p>
        <p className={styles.data}>{data?.quota || ""}</p>
      </div>
      <div>
        <p className={styles.cardtitle}>Rendimiento de la cola</p>
        <p className={styles.cardDescription}>
          Porcentaje de llenado de la cola.
        </p>
        <p className={styles.data}>{data?.performance || 0}%</p>
      </div>
    </div>
  );
}
