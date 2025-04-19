import { Suspense, useEffect } from 'react'
import { Routes, Route, useNavigate } from 'react-router-dom'
import { ToastContainer } from 'react-toastify'
import { PersistGate } from 'redux-persist/integration/react'
import { persistor } from './redux/store'
import 'react-toastify/dist/ReactToastify.css'
import userRoutes from './routes/userRoutes.jsx'
import adminRoutes from './routes/adminRoutes.jsx'
import Loading from './components/common/Loading/Loading'
import { setNavigate } from './config/authInterceptor'

const App = () => {
  const navigate = useNavigate();

  useEffect(() => {
    setNavigate(navigate);
  }, [navigate]);

  return (
    <PersistGate loading={<Loading />} persistor={persistor}>
      <Suspense fallback={<Loading />}>
        <Routes>
          {userRoutes.map((route) => (
            <Route
              key={route.path}
              path={route.path}
              element={route.element}
            >
              {route.children?.map((child) => (
                <Route
                  key={`${route.path}-${child.path}`}
                  path={child.path}
                  element={child.element}
                />
              ))}
            </Route>
          ))}
          {adminRoutes.map((route) => (
            <Route
              key={route.path}
              path={route.path}
              element={route.element}
            >
              {route.children?.map((child) => (
                <Route
                  key={`${route.path}-${child.path}`}
                  path={child.path}
                  element={child.element}
                />
              ))}
            </Route>
          ))}
        </Routes>
        <ToastContainer />
      </Suspense>
    </PersistGate>
  )
}

export default App
