import { getData, setData } from 'nuxt-storage/local-storage';

export const checkToken = () => {
    const router = useRouter()
    const userToken = getData('userToken');
    console.log(userToken)
    if (userToken) {
        router.push("/home")
    } else {
        router.push("/")
    }
}
