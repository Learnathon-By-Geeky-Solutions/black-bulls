import { Suspense, useEffect } from 'react'
import { Routes, Route, useNavigate } from 'react-router-dom'
import { ToastContainer } from 'react-toastify'
import 'react-toastify/dist/ReactToastify.css'
import userRoutes from './routes/userRoutes.jsx'
import Loading from './components/common/Loading/Loading'
import { setNavigate } from './config/authInterceptor'

const App = () => {
  const navigate = useNavigate();

  useEffect(() => {
    setNavigate(navigate);
  }, [navigate]);

  return (
    <Suspense fallback={<Loading />}>
      <Routes>
        {userRoutes.map((route, index) => (
          <Route
            key={index}
            path={route.path}
            element={route.element}
          >
            {route.children?.map((child, childIndex) => (
              <Route
                key={childIndex}
                path={child.path}
                element={child.element}
              />
            ))}
          </Route>
        ))}
      </Routes>
      <ToastContainer />
    </Suspense>
  )
}

export default App
