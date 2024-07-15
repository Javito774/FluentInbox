"use client";

import { useEffect, useRef, useState } from "react";
import styles from "./dateTimePicker.module.css";
import Calendar from "./Calendar";
import TimePicker from "./timePicker";
import { API } from "../utils";

export default function DateTimePicker({ canSend, id_mail, sendEmail }) {
  const [isOpen, setIsOpen] = useState(false);
  const [selectDate, setSelectDate] = useState(false);
  const [selectTime, setSelectTime] = useState(false);
  const [selectedDate, setSelectedDate] = useState(null);
  const [selectedHour, setSelectedHour] = useState(null);
  const menuRef = useRef(null);

  useEffect(() => {
    const handleOutsideClick = (event) => {
      if (menuRef.current && !menuRef.current.contains(event.target)) {
        setSelectDate(false);
        setSelectTime(false);
        setIsOpen(false);
      }
    };

    document.addEventListener("mousedown", handleOutsideClick);

    return () => {
      document.removeEventListener("mousedown", handleOutsideClick);
    };
  }, []);

  const toggleMenu = () => {
    setIsOpen(!isOpen);
  };

  const daySelection = (day) => {
    setSelectedDate(day);
    setSelectDate(false);
  };

  const hourSelector = (hour) => {
    setSelectedHour(hour);
    setSelectTime(false);
  };

  const clearDateTime = () => {
    daySelection(null);
    hourSelector(null);
    setIsOpen(false);
  };

  const programMail = () => {
    const date = new Date(
      selectedDate.getFullYear(),
      selectedDate.getMonth(),
      selectedDate.getDate(),
      selectedHour.getHours(),
      selectedHour.getMinutes(),
      0,
      0,
    );
    let isoString = date.toISOString();

    const raw = JSON.stringify({
      envio: isoString,
    });
    // update email
    API(`/mails/${id_mail}`, {
      method: "PUT",
      body: raw,
    }).then(() => {
      sendEmail();
    });
  };

  return (
    <div className={styles.wrapper} ref={menuRef}>
      <button
        disabled={!canSend()}
        className={`${styles.button} ${isOpen && styles.abierto}`}
        onClick={toggleMenu}
      >
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
            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"
          />
        </svg>
        Programar envío
      </button>
      {isOpen && (
        <div className={styles.pickerWindow}>
          {selectDate ? (
            <Calendar selectedDate={selectedDate} daySelection={daySelection} />
          ) : selectTime ? (
            <TimePicker
              selectedDate={selectedDate}
              selectedHour={selectedHour}
              setSelectedHour={hourSelector}
            />
          ) : (
            <>
              <p className={styles.tituloSeccion}>Programacion de Email</p>
              <p className={styles.explicacion}>
                Elige un día y una hora en la que desees que el correo
                electrónico sea enviado.
              </p>
              <div
                className={styles.input}
                onClick={() => {
                  setSelectDate(true);
                }}
              >
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
                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"
                  />
                </svg>
                <p>
                  {selectedDate
                    ? selectedDate.toLocaleString("default", {
                        day: "numeric",
                        month: "long",
                        year: "numeric",
                      })
                    : "Selecciona una fecha"}
                </p>
              </div>
              <div
                className={styles.input}
                onClick={() => {
                  setSelectTime(true);
                }}
              >
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
                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
                <p>
                  {selectedHour
                    ? selectedHour.toLocaleTimeString("es-ES", {
                        hour12: false,
                        hour: "2-digit",
                        minute: "2-digit",
                      })
                    : "Selecciona una hora"}
                </p>
              </div>
              <div className={styles.buttonsWrapper}>
                <button className={styles.button} onClick={clearDateTime}>
                  Cancelar
                </button>
                <button
                  disabled={!selectedDate || !selectedHour}
                  className={`${styles.button} ${styles.mainButton}`}
                  onClick={programMail}
                >
                  Programar
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    height="24"
                    viewBox="0 -960 960 960"
                    width="24"
                  >
                    <path d="M143.848-243.848v-473.843q0-20.076 15.961-30.615 15.962-10.538 34.039-2.923L675.46-547.307h-7.384q-26.538.77-51.191 6.654-24.654 5.884-45.961 16.038L203.846-680v147.693L420.768-480l-216.922 52.307V-280l228.694-97.385q-6.462 17.23-10.077 35.307t-4 34.768V-306.079l-224 95.769q-18.077 7.616-34.346-2.923-16.269-10.538-16.269-30.615Zm532.306 136.154q-74.923 0-127.461-52.538t-52.538-127.461q0-74.922 52.538-127.46t127.461-52.538q74.922 0 127.46 52.538t52.538 127.46q0 74.923-52.538 127.461t-127.46 52.538Zm17.692-187.23v-92.769q0-7.231-5.231-12.461-5.231-5.231-12.461-5.231-7.231 0-12.462 5.231-5.231 5.23-5.231 12.461v92.154q0 7.231 2.616 13.769 2.615 6.539 8.23 12.154l60.385 60.385q5.231 5.23 12.269 5.423 7.039.192 12.654-5.423 5.615-5.616 5.615-12.462t-5.615-12.461l-60.769-60.77Zm-490-82.461V-680v400-97.385Z" />
                  </svg>
                </button>
              </div>
            </>
          )}{" "}
        </div>
      )}
    </div>
  );
}
