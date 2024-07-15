"use client";
import { useEffect, useRef, useState } from "react";
import styles from "./layout.module.css";
import { setCookie } from "../cookies";
import { API } from "./utils";

export default function ProfileMenu() {
  const [isOpen, setIsOpen] = useState(false);
  const [userData, setUserData] = useState(null);
  const menuRef = useRef(null);

  useEffect(() => {
    const handleOutsideClick = (event) => {
      if (menuRef.current && !menuRef.current.contains(event.target)) {
        setIsOpen(false);
      }
    };

    document.addEventListener("mousedown", handleOutsideClick);

    return () => {
      document.removeEventListener("mousedown", handleOutsideClick);
    };
  }, []);

  useEffect(() => {
    API("/users/me").then((data) => {
      setUserData(data);
    });
  }, []);

  const toggleMenu = () => {
    setIsOpen(!isOpen);
  };

  function handleLogout() {
    setCookie("sessionToken", null, 0);
    window.location = "/login";
  }
  return (
    <div className={styles.profileBubbleWrapper} ref={menuRef}>
      <div className={styles.profile} onClick={toggleMenu}>
        {userData ? userData.name : ""}
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
            d="M19.5 8.25l-7.5 7.5-7.5-7.5"
          />
        </svg>
      </div>
      {isOpen && (
        <div className={styles.userMenu}>
          <ul>
            <li>Cuenta</li>
            <li className={styles.red} onClick={handleLogout}>
              Cerrar sesión
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
                  d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"
                />
              </svg>
            </li>
          </ul>
        </div>
      )}
    </div>
  );
}
