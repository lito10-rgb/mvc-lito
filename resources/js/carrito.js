import axios from "axios";

document.addEventListener("DOMContentLoaded", () => {
    const botones = document.querySelectorAll(".btn-agregar-carrito");
    const contadorCarrito = document.getElementById("contador-carrito");

    botones.forEach(boton => {
        boton.addEventListener("click", async (e) => {
            e.preventDefault();
            const url = boton.dataset.url;

            try {
                await axios.post(url);
                const response = await axios.get("/carrito/count");
                contadorCarrito.textContent = response.data.count;
            } catch (error) {
                console.error("Error al agregar producto al carrito:", error);
            }
        });
    });
});
