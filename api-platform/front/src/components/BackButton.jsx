import { ArrowLeftIcon } from "@heroicons/react/24/solid";
import { Link } from "react-router-dom";

function BackButton({ to }) {
    return (
        <Link to={to}>
            <button className="rounded-full bg-gray-200 p-2 hover:bg-gray-300">
                <ArrowLeftIcon className="w-6 h-6" />
            </button>
        </Link>
    );
}

export default BackButton;
