"use client";
import { useEffect, useState } from "react";
import styles from "../../page.module.css";
import { API } from "../../utils";
import Link from "next/link";

export default function Page() {
  const [data, setData] = useState([]);
  const [numSelected, setNumSelected] = useState(0);

  useEffect(() => {
    API(
      "/mails?filters[$and][0][estado][$ne]=draft&filters[$and][1][intrash][$eq]=0&fields[0]=id&fields[1]=asunto&fields[2]=body&fields[3]=updated_at&sort[field]=updated_at&sort[order]=DESC",
    )
      .then((data) => setData(data))
      .catch((err) => console.error(err));
  }, []);

  function handleLinkOpen(e) {
    if (e.target.classList.contains("button")) e.preventDefault();
  }

  function addToSelected(index) {
    console.log(index);
    const aux = [...data];
    aux[index].selected = true;
    setData(aux);
    setNumSelected(numSelected + 1);
  }

  function removeSelected(index) {
    const aux = [...data];
    aux[index].selected = false;
    setData(aux);
    setNumSelected(numSelected - 1);
  }

  function selectAll() {
    const aux = [...data];
    aux.forEach((mail) => {
      mail.selected = true;
    });
    setData(aux);
    setNumSelected(aux.length);
  }

  function deselectAll() {
    const aux = [...data];
    aux.forEach((mail) => {
      mail.selected = false;
    });
    setData(aux);
    setNumSelected(0);
  }

  function moveToTrash() {
    const aux = [];
    data.forEach((mail) => {
      if (mail.selected) {
        const raw = JSON.stringify({
          intrash: true,
        });
        API(`/mails/${mail.id}`, {
          method: "PUT",
          body: raw,
        });
      } else {
        aux.push(mail);
      }
    });
    setData(aux);
    setNumSelected(0);
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
      <div className={styles.buttonsWrapper}>
        {numSelected == 0 ? (
          <img
            alt="check-box"
            src="/check_box_outline_blank_FILL0_wght700_GRAD0_opsz48.svg"
            onClick={selectAll}
          />
        ) : numSelected >= data.length ? (
          <img
            alt="check-box"
            src="/check_box_FILL0_wght700_GRAD0_opsz48.svg"
            onClick={deselectAll}
          />
        ) : (
          <img
            alt="check-box"
            src="/indeterminate_check_box_FILL0_wght400_GRAD0_opsz24.svg"
            onClick={deselectAll}
          />
        )}
        <svg
          id="refresh2"
          xmlns="http://www.w3.org/2000/svg"
          height="20px"
          viewBox="0 0 24 24"
          width="20px"
          fill="#000000"
        >
          <path d="M0 0h24v24H0V0z" fill="none"></path>
          <path d="M17.65 6.35c-1.63-1.63-3.94-2.57-6.48-2.31-3.67.37-6.69 3.35-7.1 7.02C3.52 15.91 7.27 20 12 20c3.19 0 5.93-1.87 7.21-4.56.32-.67-.16-1.44-.9-1.44-.37 0-.72.2-.88.53-1.13 2.43-3.84 3.97-6.8 3.31-2.22-.49-4.01-2.3-4.48-4.52C5.31 9.44 8.26 6 12 6c1.66 0 3.14.69 4.22 1.78l-1.51 1.51c-.63.63-.19 1.71.7 1.71H19c.55 0 1-.45 1-1V6.41c0-.89-1.08-1.34-1.71-.71l-.64.65z"></path>
        </svg>
        {numSelected > 0 && (
          <svg
            onClick={moveToTrash}
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-6 h-6"
            width={20}
            height={20}
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
            ></path>
          </svg>
        )}
      </div>
      <div className={styles.mailsWrapper}>
        <ol>
          {data.map((mail, index) => (
            <li key={mail.id}>
              <Link
                href={`/mail?id=${mail.id}`}
                onClick={handleLinkOpen}
                className={`${styles.mail}`}
              >
                {!mail.selected ? (
                  <img
                    onClick={() => addToSelected(index)}
                    alt="check-box"
                    src="/check_box_outline_blank_FILL0_wght700_GRAD0_opsz48.svg"
                    className="button"
                  />
                ) : (
                  <img
                    onClick={() => removeSelected(index)}
                    alt="box-checked"
                    src="/check_box_FILL0_wght700_GRAD0_opsz48.svg"
                    className="button"
                  />
                )}

                <p className={styles.asuntoMailList}>{mail.asunto}</p>
                <span className={styles.bodyMailList}>{mail.body}</span>
                <span className={styles.dateMailList}>
                  {formatDate(mail.updated_at)}
                </span>
              </Link>
            </li>
          ))}
        </ol>
        <p className={styles.notMore}>No hay mas mails en esta bandeja</p>
      </div>
    </>
  );
}
