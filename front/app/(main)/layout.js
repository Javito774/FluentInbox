"use client";
import "../globals.css";
import styles from "./layout.module.css";
import { Inter } from "next/font/google";
import { useEffect, useState } from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import ProfileMenu from "./profileMenu";
import { API } from "./utils";
import Performance from "./performanceStats";

const inter = Inter({ subsets: ["latin"] });

export default function RootLayout({ children }) {
  const pathname = usePathname();
  const [selectedPage, setSelectedPage] = useState("none");

  const pages = {
    "/mails/inbox": "inbox",
    "/mails/sent": "sent",
    "/mails/scheduled": "scheduled",
    "/mails/snoozed": "snoozed",
    "/mails/draft": "drafts",
    "/mails/trash": "trash",
    "/settings": "settings",
    "/new-mail": "none",
  };

  useEffect(() => {
    if (pages[pathname]) setSelectedPage(pages[pathname]);
  }, [pathname]);

  function handleComposeClick() {
    API("/mails", {
      method: "POST",
    })
      .then(
        (data) => (window.location.href = "/compose?id=" + data.inserted_id),
      )
      .catch((err) => console.error(err));
  }

  return (
    <html lang="es">
      <body className={inter.className}>
        <aside className={styles.aside}>
          <div className={styles.branding}>
            <img alt="AsiMadrid logo" src="/Logo.svg" />
            <h1>FluentInbox</h1>
          </div>
          <div className={styles.asideContent}>
            <section>
              <div
                onClick={handleComposeClick}
                className={styles.buttonRedactar}
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  width={24}
                  viewBox="0 0 24 24"
                  strokeWidth={1.5}
                  stroke="currentColor"
                  className="w-6 h-6"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                  />
                </svg>
                Redactar
              </div>
              <ul>
                <li>
                  <Link
                    href="/mails/inbox"
                    className={
                      selectedPage === "inbox" ? styles.selected : undefined
                    }
                  >
                    <img src="/inbox_FILL0_wght300_GRAD0_opsz24.svg" />
                    Todos
                  </Link>
                </li>
                <li>
                  <Link
                    href="/mails/sent"
                    className={
                      selectedPage === "sent" ? styles.selected : undefined
                    }
                  >
                    <img src="/send_FILL0_wght300_GRAD0_opsz24.svg" />
                    Enviados
                  </Link>
                </li>
                <li>
                  <Link
                    href="/mails/scheduled"
                    className={
                      selectedPage === "scheduled" ? styles.selected : undefined
                    }
                  >
                    <img src="/schedule_send_FILL0_wght300_GRAD0_opsz24.svg" />
                    Programados
                  </Link>
                </li>
                <li>
                  <Link
                    href="/mails/snoozed"
                    className={
                      selectedPage === "snoozed" ? styles.selected : undefined
                    }
                  >
                    <img src="/outbox_FILL0_wght300_GRAD0_opsz24.svg" />
                    Bandeja de salida
                  </Link>
                </li>
                <li>
                  <Link
                    href="/mails/draft"
                    className={
                      selectedPage === "drafts" ? styles.selected : undefined
                    }
                  >
                    <img src="/draft_FILL0_wght300_GRAD0_opsz24.svg" />
                    Borradores
                  </Link>
                </li>
                <li>
                  <Link
                    href="/mails/trash"
                    className={
                      selectedPage === "trash" ? styles.selected : undefined
                    }
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      strokeWidth={1.5}
                      stroke="currentColor"
                      height="22"
                      width="24"
                    >
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                      />
                    </svg>
                    Papelera
                  </Link>
                </li>
              </ul>
            </section>
            <a
              href="/settings"
              className={
                selectedPage === "settings" ? styles.selected : undefined
              }
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                width={24}
                viewBox="0 0 24 24"
                strokeWidth={1.5}
                stroke="currentColor"
                className="w-6 h-6"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"
                />
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                />
              </svg>
              Ajustes
            </a>
          </div>
        </aside>
        <main className={styles.principalContentWrapper}>
          <header className={styles.header}>
            <div className={styles.buscador}>
              <form>
                <button type="submit">
                  <img src="/search_FILL0_wght300_GRAD0_opsz24.svg"></img>
                </button>
                <input name="s" type="search" placeholder="Buscar ..."></input>
              </form>
            </div>
            <div className={styles.buttonsRight}>
              <Performance />
              <ProfileMenu />
            </div>
          </header>
          <div className={styles.contentWrapper}>{children}</div>
        </main>
      </body>
    </html>
  );
}
