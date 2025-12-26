import api from "@/lib/api";

export async function calendarRequest(payload) {
    return api.post("/api/v1/citas/store" , payload);
}
