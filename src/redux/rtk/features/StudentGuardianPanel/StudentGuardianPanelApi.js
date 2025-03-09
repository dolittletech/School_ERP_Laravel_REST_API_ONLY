import { toastHandler } from "../../../../utils/functions";
import { apiSlice } from "../api/apiSlice";


export const studentGuardianPanelApi = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    updateStudentGuardianPanel: builder.mutation({
      query: (formData) => ({
        url: "setting/general-setting/update-student-guardian", // Match the observed URL
        method: "POST",
        body: formData,
      }),
      async onQueryStarted(arg, { queryFulfilled }) {
        try {
          const { data } = await queryFulfilled;
          toastHandler(data.message, "success");
        } catch (err) {
          toastHandler("Failed to update student/guardian panel settings, please try again", "warning");
        }
      },
      invalidatesTags: ["setting"],
    }),
  }),
});

export const { useUpdateStudentGuardianPanelMutation } = studentGuardianPanelApi;