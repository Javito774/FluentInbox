"use client";
import { useSearchParams } from "next/navigation";
import { useEffect, useRef, useState } from "react";
import { API } from "../utils";
import styles from "./page.module.css";

export default function Mail() {
  const searchParams = useSearchParams();
  const id = searchParams.get("id");
  const [mailHTML, setMailHTML] = useState(null);
  const [mailAsunto, setMailAsunto] = useState(null);
  const [mailDestinatarios, setMailDestinatarios] = useState([]);
  const contenido = useRef(null);

  useEffect(() => {
    if (id) {
      API(
        `/mails/${id}?populate[0]=centros&populate[1]=destinatarios&populate[2]=listas`,
      )
        .then((data) => {
          if (data.estado == "draft" && data.intrash == false) {
            window.location.href = "/compose?id=" + data.id;
          } else {
            setMailHTML(data.bodyhtml);
            setMailAsunto(data.asunto);
            setMailDestinatarios(
              [
                ...data.listas.map((e) => ({ ...e, type: "lista" })),
                ...data.centros.map((e) => ({ ...e, type: "centro" })),
                ...data.destinatarios.map((e) => ({
                  ...e,
                  type: "destinatario",
                })),
              ] || [],
            );
          }
        })
        .catch((err) => console.error(err));
    }
  }, [id]);

  useEffect(() => {
    if (contenido.current) {
      contenido.current.innerHTML = mailHTML;
    }
  }, [mailHTML]);

  function moveToTrash() {
    if (id) {
      const raw = JSON.stringify({
        intrash: true,
      });
      API(`/mails/${id}`, {
        method: "PUT",
        body: raw,
      }).then(() => {
        window.location = "/mails/draft";
      });
    }
  }

  return (
    <>
      <div
        className={`${styles.wrapper} ${styles.flex} ${styles.headerWrapper}`}
      >
        <div className={`${styles.flex}`}>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="#000000"
            className="w-5 h-5"
          >
            <path d="M15.98 1.804a1 1 0 00-1.96 0l-.24 1.192a1 1 0 01-.784.785l-1.192.238a1 1 0 000 1.962l1.192.238a1 1 0 01.785.785l.238 1.192a1 1 0 001.962 0l.238-1.192a1 1 0 01.785-.785l1.192-.238a1 1 0 000-1.962l-1.192-.238a1 1 0 01-.785-.785l-.238-1.192zM6.949 5.684a1 1 0 00-1.898 0l-.683 2.051a1 1 0 01-.633.633l-2.051.683a1 1 0 000 1.898l2.051.684a1 1 0 01.633.632l.683 2.051a1 1 0 001.898 0l.683-2.051a1 1 0 01.633-.633l2.051-.683a1 1 0 000-1.898l-2.051-.683a1 1 0 01-.633-.633L6.95 5.684zM13.949 13.684a1 1 0 00-1.898 0l-.184.551a1 1 0 01-.632.633l-.551.183a1 1 0 000 1.898l.551.183a1 1 0 01.633.633l.183.551a1 1 0 001.898 0l.184-.551a1 1 0 01.632-.633l.551-.183a1 1 0 000-1.898l-.551-.184a1 1 0 01-.633-.632l-.183-.551z" />
          </svg>

          <h3>{mailAsunto}</h3>
        </div>
        <div className={`${styles.flex}`}>
          <button className={`${styles.button}`} onClick={moveToTrash}>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              strokeWidth={1.5}
              stroke="currentColor"
              className="w-6 h-6"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
              />
            </svg>
          </button>
        </div>
      </div>
      <div className={`${styles.wrapper} ${styles.mailbody}`}>
        <div className={`${styles.flex} ${styles.maillabelwrapper}`}>
          <p className={styles.mailLabel}>De</p>
          <div className={styles.mailBadgesWrapper}>
            <p className={styles.mailBadge}>noreply@fluentinbox.com</p>
          </div>
        </div>
        <div className={`${styles.flex} ${styles.maillabelwrapper}`}>
          <p className={styles.mailLabel}>Para</p>
          <div className={styles.mailBadgesWrapper}>
            {mailDestinatarios.map((dest, index) => (
              <p
                key={index}
                className={`${styles.mailBadge} ${
                  dest.type === "lista" && styles.lista
                } ${dest.type === "centro" && styles.centro}`}
              >
                {dest.email ? dest.email : dest.nombre}
              </p>
            ))}
          </div>
        </div>
        <div className={`${styles.flex} ${styles.fullWidth}`}>
          <div
            className={styles.editorWrapper}
            style={{ backgroundColor: "#f1f5f9", padding: "3px 0" }}
          >
            <table
              cellSpacing="0"
              cellPadding="0"
              border="0"
              align="center"
              width="100%"
              style={{
                maxWidth: 600 + "px",
                width: 100 + "%",
                background: "#ffffff",
              }}
            >
              <tbody>
                <tr>
                  <td>
                    <div
                      ref={contenido}
                      className={styles.editable}
                      style={{
                        padding: "30px 50px",
                      }}
                    ></div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </>
  );
}
