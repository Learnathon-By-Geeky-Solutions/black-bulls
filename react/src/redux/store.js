import { configureStore } from '@reduxjs/toolkit';
import { persistStore, persistReducer } from 'redux-persist';
import storage from 'redux-persist/lib/storage';
import authReducer from './reducers/authReducer';
import userReducer from './slices/userSlice';
import studyReducer from './slices/studySlice';
import courseReducer from './slices/courseSlice';

const persistConfig = {
  key: 'root',
  storage,
  whitelist: ['user'] // Only persist the user reducer
};

const persistedUserReducer = persistReducer(persistConfig, userReducer);

export const store = configureStore({
  reducer: {
    auth: authReducer,
    user: persistedUserReducer,
    study: studyReducer,
    course: courseReducer, // Add course reducer
  },
  middleware: (getDefaultMiddleware) =>
    getDefaultMiddleware({
      serializableCheck: {
        ignoredActions: ['persist/PERSIST', 'persist/REHYDRATE']
      }
    })
});

export const persistor = persistStore(store);

export default store;