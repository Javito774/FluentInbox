"use client";

import { useEffect, useRef, useState } from "react";
import styles from "./linkModal.module.css";

export default function AddLink({ handleAddLink }) {
  const [isOpen, setIsOpen] = useState(false);
  const menuRef = useRef(null);
  const [url, setUrl] = useState(null);
  const savedSelection = useRef(null);

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

  const saveSelection = () => {
    const selection = window.getSelection();
    if (selection.rangeCount > 0) {
      savedSelection.current = selection.getRangeAt(0).cloneRange();
    }
  };

  const restoreSelection = () => {
    if (savedSelection.current) {
      const selection = window.getSelection();
      selection.removeAllRanges();
      selection.addRange(savedSelection.current);
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    restoreSelection();
    handleAddLink(url);
    setIsOpen(false);
  }

  const handleInput = (e) => {
    setUrl(e.target.value);
  }


  const toggleMenu = () => {
    saveSelection();
    setIsOpen(!isOpen);
  };

  return (
    <div className={styles.wrapper} ref={menuRef}>
      <button onClick={toggleMenu}>
        <img src="/link_FILL1_wght300_GRAD0_opsz24.svg" />
      </button>
      {isOpen && (
        <div className={styles.colorPickerMenu}>
          <form onSubmit={handleSubmit}>
            <input className={styles.input} type="url" onChange={handleInput}/>
            <button type="submit">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                <path strokeLinecap="round" strokeLinejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
              </svg>
            </button>
          </form>
        </div>
      )}
    </div>
  );
}
