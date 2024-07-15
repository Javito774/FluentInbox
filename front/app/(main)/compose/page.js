"use client";

import { useSearchParams } from "next/navigation";
import styles from "./page.module.css";
import { useEffect, useRef, useState } from "react";
import { API } from "../utils";
import Editor from "./editor";
import DateTimePicker from "./dateTimePicker";

export default function NewMail() {
  const searchParams = useSearchParams();
  const id = searchParams.get("id");
  const [mailBody, setMailBody] = useState(null);
  const [mailHTML, setMailHTML] = useState(null);
  const [mailAsunto, setMailAsunto] = useState(null);
  const [mailDestinatarios, setMailDestinatarios] = useState([]);
  const [connDest, setConnDest] = useState([]);
  const [disDest, setDisDest] = useState([]);
  const [connList, setConnList] = useState([]);
  const [disList, setDisList] = useState([]);
  const [connCent, setConnCent] = useState([]);
  const [disCent, setDisCent] = useState([]);
  const [allDestinatarios, setAllDestinatarios] = useState([]);
  const [allCentros, setAllCentros] = useState([]);
  const [allListas, setAllListas] = useState([]);
  const [showDestinatarios, setShowDestinatarios] = useState(false);
  const [currentValue, setCurrentValue] = useState("");
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [sending, setSending] = useState(false);
  const [lastSave, setLastSave] = useState(null)


  useEffect(() => {
    API("/destinatarios")
      .then((data) => {
        const formatted = data.map((element) => ({
          ...element,
          type: "destinatario",
        }));
        setAllDestinatarios(formatted);
      })
      .catch((err) => console.error(err));
    API("/listas")
      .then((data) => {
        const formatted = data.map((element) => ({
          ...element,
          type: "lista",
        }));
        setAllListas(formatted);
      })
      .catch((err) => console.error(err));
    API("/centros?fields[0]=id&fields[1]=nombre")
      .then((data) => {
        const formatted = data.map((element) => ({
          ...element,
          type: "centro",
        }));
        setAllCentros(formatted);
      })
      .catch((err) => console.error(err));
  }, []);

  useEffect(() => {
    if (id) {
      API(
        `/mails/${id}?populate[0]=centros&populate[1]=destinatarios&populate[2]=listas`,
      )
        .then((data) => {
          if (data.estado == "draft" && data.intrash == false) {
            setMailBody(data.body);
            setMailHTML(data.bodyhtml);
            setMailAsunto(data.asunto);
            setLastSave(data.updated_at);
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
            setLoading(false);
          } else {
            window.location.href = "/mail?id=" + data.id;
          }
        })
        .catch((err) => console.error(err));
    }
  }, [id]);

  function canSendTest() {
    return mailBody && mailAsunto && !saving && !sending;
  }

  function canSend() {
    return canSendTest() && mailDestinatarios.length > 0;
  }

  function saveData() {
    if (id) {
      return new Promise((resolve, reject) => {
        setSaving(true);
        const raw = JSON.stringify({
          asunto: mailAsunto,
          body: mailBody,
          bodyhtml: mailHTML,
          destinatarios: {
            connect: connDest,
            disconnect: disDest,
          },
          centros: {
            connect: connCent,
            disconnect: disCent,
          },
          listas: {
            connect: connList,
            disconnect: disList,
          },
        });
        // update email
        API(`/mails/${id}`, {
          method: "PUT",
          body: raw,
        })
          .then((response) => {
            resolve(response);
            setLastSave(new Date());
          })
          .catch((error) => {
            reject(error);
          })
          .finally(() => {
            setSaving(false);
          });
      });
    } else {
      return Promise.resolve();
    }
  }

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

  function handleAsuntoChange(e) {
    if (e.target.value.trim().length > 0) {
      setMailAsunto(e.target.value);
    } else {
      setMailAsunto("");
    }
  }

  function validate_email(emailString) {
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailRegex.test(emailString);
  }

  function isSelectDestinatario(array, newElement) {
    return array.some(
      (element) =>
        element.type === newElement.type &&
        element.destinatario === newElement.destinatario,
    );
  }

  function addDestinatarioNoList(e) {
    if (e.key === "Enter" || e.type === "blur") {
      const newArray = [...mailDestinatarios];
      const newConnect = [...connDest];
      const newDisconnect = [...disDest];
      let correos = currentValue.trim().split(" ");
      correos.forEach((correo) => {
        const found = allDestinatarios.find(
          (element) => element.email === correo,
        );
        if (found) {
          let indexDis = newDisconnect.indexOf(found.id);
          let indexCon = newConnect.indexOf(found.id);
          if (indexDis !== -1) {
            newDisconnect.splice(indexDis, 1);
            newArray.push(found);
          } else if (indexCon === -1) {
            newConnect.push(found.id);
            newArray.push(found);
          }
        } else {
          if (validate_email(correo))
            API("/destinatarios", {
              method: "POST",
              body: JSON.stringify({
                email: correo,
              }),
            }).then((data) => {
              allDestinatarios.push({
                id: data.inserted_id,
                email: correo,
              });
              newArray.push({
                id: data.inserted_id,
                email: correo,
              });
              newConnect.push(data.inserted_id);
              setConnDest(newConnect);
              setMailDestinatarios(newArray);
              setCurrentValue("");
              setShowDestinatarios(false);
            });
        }
      });
      setMailDestinatarios(newArray);
      setConnDest(newConnect);
      setDisDest(newDisconnect);
      setCurrentValue("");
      setShowDestinatarios(false);
    }
  }

  function addDestinatario(id) {
    const found = allDestinatarios.find((element) => element.id === id);
    if (found) {
      const newArray = [...mailDestinatarios];
      const newConnect = [...connDest];
      const newDisconnect = [...disDest];
      let indexDis = newDisconnect.indexOf(id);
      let indexCon = newConnect.indexOf(id);
      if (indexDis !== -1) {
        newDisconnect.splice(indexDis, 1);
        newArray.push(found);
      } else if (indexCon === -1) {
        newConnect.push(found.id);
        newArray.push(found);
      }
      setMailDestinatarios(newArray);
      setConnDest(newConnect);
      setDisDest(newDisconnect);
    }
    setCurrentValue("");
    setShowDestinatarios(false);
  }

  function removeDestinatario(id) {
    const found = mailDestinatarios.findIndex(
      (element) => element.id === id && element.type === "destinatario",
    );
    if (found !== -1) {
      const newArray = [
        ...mailDestinatarios.slice(0, found),
        ...mailDestinatarios.slice(found + 1),
      ];
      const newConnect = [...connDest];
      const newDisconnect = [...disDest];
      let indexDis = newDisconnect.indexOf(id);
      let indexCon = newConnect.indexOf(id);
      if (indexCon !== -1) {
        newConnect.splice(indexCon, 1);
      } else if (indexDis === -1) {
        newDisconnect.push(id);
      }
      setMailDestinatarios(newArray);
      setConnDest(newConnect);
      setDisDest(newDisconnect);
    }
  }

  function addLista(id) {
    const found = allListas.find((element) => element.id === id);
    if (found) {
      const newArray = [...mailDestinatarios];
      const newConnect = [...connList];
      const newDisconnect = [...disList];
      let indexDis = newDisconnect.indexOf(id);
      let indexCon = newConnect.indexOf(id);
      if (indexDis !== -1) {
        newDisconnect.splice(indexDis, 1);
        newArray.push(found);
      } else if (indexCon === -1) {
        newConnect.push(found.id);
        newArray.push(found);
      }
      setMailDestinatarios(newArray);
      setConnList(newConnect);
      setDisList(newDisconnect);
    }
    setCurrentValue("");
    setShowDestinatarios(false);
  }

  function removeLista(id) {
    const found = mailDestinatarios.findIndex(
      (element) => element.id === id && element.type === "lista",
    );
    if (found !== -1) {
      const newArray = [
        ...mailDestinatarios.slice(0, found),
        ...mailDestinatarios.slice(found + 1),
      ];
      const newConnect = [...connList];
      const newDisconnect = [...disList];
      let indexDis = newDisconnect.indexOf(id);
      let indexCon = newConnect.indexOf(id);
      if (indexCon !== -1) {
        newConnect.splice(indexCon, 1);
      } else if (indexDis === -1) {
        newDisconnect.push(id);
      }
      setMailDestinatarios(newArray);
      setConnList(newConnect);
      setDisList(newDisconnect);
    }
  }

  function addCentro(id) {
    const found = allCentros.find((element) => element.id === id);
    if (found) {
      const newArray = [...mailDestinatarios];
      const newConnect = [...connCent];
      const newDisconnect = [...disCent];
      let indexDis = newDisconnect.indexOf(id);
      let indexCon = newConnect.indexOf(id);
      if (indexDis !== -1) {
        newDisconnect.splice(indexDis, 1);
        newArray.push(found);
      } else if (indexCon === -1) {
        newConnect.push(found.id);
        newArray.push(found);
      }
      setMailDestinatarios(newArray);
      setConnCent(newConnect);
      setDisCent(newDisconnect);
    }
    setCurrentValue("");
    setShowDestinatarios(false);
  }

  function removeCentro(id) {
    const found = mailDestinatarios.findIndex(
      (element) => element.id === id && element.type === "centro",
    );
    if (found !== -1) {
      const newArray = [
        ...mailDestinatarios.slice(0, found),
        ...mailDestinatarios.slice(found + 1),
      ];
      const newConnect = [...connCent];
      const newDisconnect = [...disCent];
      let indexDis = newDisconnect.indexOf(id);
      let indexCon = newConnect.indexOf(id);
      if (indexCon !== -1) {
        newConnect.splice(indexCon, 1);
      } else if (indexDis === -1) {
        newDisconnect.push(id);
      }
      setMailDestinatarios(newArray);
      setConnCent(newConnect);
      setDisCent(newDisconnect);
    }
  }

  function removeGeneralDestinatario(index) {
    const data = mailDestinatarios[index];
    console.log(data);
    if (data.type === "destinatario") {
      removeDestinatario(data.id);
    } else if (data.type === "lista") {
      removeLista(data.id);
    } else if (data.type === "centro") {
      removeCentro(data.id);
    }
  }

  function handleInput(e) {
    setCurrentValue(e.target.value);
  }

  function sendEmail() {
    setSending(true);
    saveData().then(() => {
      API(`/mails/send/${id}`, {
        method: "POST",
      }).then(() => {
          window.location.href = "/";
        }).finally(() => setSending(false));
    });
  }

  function sendTestEmail() {
    setSending(true);
    saveData().then(() => {
      API(`/mails/sendTest/${id}`, {
        method: "POST",
      }).then(() => {
          console.log("Test del mail enviado.");
        }).finally(() => setSending(false));
    });
  }

  function formatDate(inputDate) {
    const currentDate = new Date();
    const inputDateObj = new Date(inputDate);

    if (isSameDay(currentDate, inputDateObj)) {
      // Same day
      return formatTime(inputDateObj);
    } else if (isSameYear(currentDate, inputDateObj)) {
      // Same year
      return formatDayMonth(inputDateObj);
    } else {
      // Different year
      return formatFullDate(inputDateObj);
    }
  }

  function isSameDay(date1, date2) {
    return (
      date1.getDate() === date2.getDate() &&
        date1.getMonth() === date2.getMonth() &&
        date1.getFullYear() === date2.getFullYear()
    );
  }

  function isSameYear(date1, date2) {
    return date1.getFullYear() === date2.getFullYear();
  }

  function formatTime(date) {
    const hours = date.getHours().toString().padStart(2, "0");
    const minutes = date.getMinutes().toString().padStart(2, "0");
    return `${hours}:${minutes}`;
  }

  function formatDayMonth(date) {
    const day = date.getDate();
    const month = date.toLocaleString("en-us", { month: "short" });
    return `${day} ${month}`;
  }

  function formatFullDate(date) {
    const day = date.getDate();
    const month = date.toLocaleString("en-us", { month: "short" });
    const year = date.getFullYear();
    return `${day} ${month} ${year}`;
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

          <h3>Nuevo Email</h3>
        </div>
        <div className={`${styles.flex}`}>
          <span className={styles.lastSave}>Ultimo cambio guardado: {formatDate(lastSave)}</span>

          <button className={`${styles.button}`} disabled={saving || sending} onClick={moveToTrash}>
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
          <button className={`${styles.button}`} disabled={saving || sending} onClick={saveData}>
            { !saving ? (
            <>
              <svg
              xmlns="http://www.w3.org/2000/svg"
              height="24"
              viewBox="0 -960 960 960"
              width="24"
              stroke="currentColor"
              fill="currentColor"
            >
              <path d="M212.309-140.001q-30.308 0-51.308-21t-21-51.308v-535.382q0-30.308 21-51.308t51.308-21h429.306q14.461 0 27.807 5.616 13.347 5.615 23.193 15.461l106.307 106.307q9.846 9.846 15.461 23.193 5.616 13.346 5.616 27.807v429.306q0 30.308-21 51.308t-51.308 21H212.309ZM760-646 646-760H212.309q-5.385 0-8.847 3.462-3.462 3.462-3.462 8.847v535.382q0 5.385 3.462 8.847 3.462 3.462 8.847 3.462h535.382q5.385 0 8.847-3.462 3.462-3.462 3.462-8.847V-646ZM480-269.233q41.538 0 70.768-29.23 29.231-29.231 29.231-70.768 0-41.538-29.231-70.769-29.23-29.23-70.768-29.23T409.232-440q-29.231 29.231-29.231 70.769 0 41.537 29.231 70.768 29.23 29.23 70.768 29.23ZM291.539-564.616h256.152q15.462 0 25.808-10.346t10.346-25.807v-67.692q0-15.461-10.346-25.807-10.346-10.346-25.808-10.346H291.539q-15.461 0-25.807 10.346-10.346 10.346-10.346 25.807v67.692q0 15.461 10.346 25.807 10.346 10.346 25.807 10.346ZM200-646V-200-760v114Z" />
            </svg>
            Guardar
            </>
            ):(
                <>
                Guardando...
                </>
              ) }
          </button>
          <DateTimePicker canSend={canSend}/>
          <button
            disabled={!canSend()}
            className={`${styles.button} ${styles.mainButton}`}
            onClick={sendEmail}
          >
            Enviar
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="24"
              viewBox="0 -960 960 960"
              width="24"
              stroke="currentColor"
              fill="currentColor"
            >
              <path d="M748.92-446.462 190.616-211.079q-18.076 7.231-34.345-3.115-16.27-10.346-16.27-30.039v-471.534q0-19.693 16.27-30.039 16.269-10.346 34.345-3.115L748.92-513.538q22.307 9.846 22.307 33.538 0 23.692-22.307 33.538ZM200-280l474-200-474-200v147.693L416.921-480 200-427.693V-280Zm0 0v-400 400Z" />
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
                <button
                  type="button"
                  className={styles.closeButton}
                  onClick={() => removeGeneralDestinatario(index)}
                >
                  <svg
                    viewBox="0 0 14 14"
                    width={10}
                    height={10}
                    stroke="currentColor"
                  >
                    <path d="M4 4l6 6m0-6l-6 6"></path>
                  </svg>
                </button>
              </p>
            ))}
            <div className={styles.destinputWrapper}>
              <input
                type="text"
                className={styles.inputUsers}
                onKeyDown={addDestinatarioNoList}
                onBlur={addDestinatarioNoList}
                onFocus={() => setShowDestinatarios(true)}
                onInput={handleInput}
                value={currentValue}
              />
              {showDestinatarios && (
                <div className={styles.destinatariosFilter}>
                  <>
                    {allListas
                      .filter((data) =>
                        data.nombre
                          .toLowerCase()
                          .includes(currentValue.toLowerCase()),
                      )
                      .slice(0, 10)
                      .map((data) => (
                        <div
                          key={data.id}
                          className={styles.destSelector}
                          onMouseDown={() => addLista(data.id)}
                        >
                          <img src="star_FILL1_wght300_GRAD0_opsz24.svg" />
                          {data.nombre}
                        </div>
                      ))}
                  </>
                  <>
                    {allCentros
                      .filter((data) =>
                        data.nombre
                          .toLowerCase()
                          .includes(currentValue.toLowerCase()),
                      )
                      .slice(0, 10)
                      .map((data) => (
                        <div
                          key={data.id}
                          className={styles.destSelector}
                          onMouseDown={() => addCentro(data.id)}
                        >
                          <img src="school_FILL1_wght300_GRAD0_opsz24.svg" />
                          {data.nombre}
                        </div>
                      ))}
                  </>
                  <>
                    {allDestinatarios
                      .filter((data) =>
                        data.email
                          .toLowerCase()
                          .includes(currentValue.toLowerCase()),
                      )
                      .slice(0, 10)
                      .map((data) => (
                        <div
                          key={data.id}
                          className={styles.destSelector}
                          onMouseDown={() => addDestinatario(data.id)}
                        >
                          <img src="alternate_email_FILL1_wght300_GRAD0_opsz24.svg" />
                          {data.email}
                        </div>
                      ))}
                  </>
                </div>
              )}
            </div>
          </div>
        </div>
        <div
          className={`${styles.flex} ${styles.maillabelwrapper}`}
          style={{ border: 0 }}
        >
          <p className={styles.mailLabel}>Asunto</p>
          <input
            className={styles.input}
            type="text"
            value={mailAsunto || ""}
            onChange={handleAsuntoChange}
          />
        </div>
        <div className={`${styles.flex} ${styles.fullWidth}`}>
          <div
            className={styles.editorWrapper}
            style={{ backgroundColor: "#f1f5f9", padding: "3px 0" }}
          >
            <Editor
              mailHTML={mailHTML}
              setMailHTML={setMailHTML}
              setMailBody={setMailBody}
              loading={loading}
            ></Editor>
          </div>
        </div>
      </div>
    </>
  );
}
