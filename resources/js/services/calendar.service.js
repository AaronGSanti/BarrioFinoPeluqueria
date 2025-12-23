import api from "@/lib/api";

export async function calendarRequest(payload) {
    return api.post("/api/citas/store" , payload);
}
