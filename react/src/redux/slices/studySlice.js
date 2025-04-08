import { createSlice } from '@reduxjs/toolkit';

const initialState = {
    activeLesson: null,
    activeContentType: null // 'lesson' or 'quiz'
};

const studySlice = createSlice({
    name: 'study',
    initialState,
    reducers: {
        setActiveLesson: (state, action) => {
            state.activeLesson = action.payload.id;
            state.activeContentType = action.payload.type;
        },
        clearActiveLesson: (state) => {
            state.activeLesson = null;
            state.activeContentType = null;
        }
    }
});

export const { setActiveLesson, clearActiveLesson } = studySlice.actions;
export default studySlice.reducer; 