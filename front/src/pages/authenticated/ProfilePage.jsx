import React, { useEffect, useState } from "react";
import UserProfileField from "../../components/profile/UserProfileField";
import PasswordChanger from "../../components/profile/PasswordChanger";
import DefaultLayout from "../../layouts/DefaultLayout";
import { useUser } from "../../hooks/useUser";
import ReservationsSection from "../../sections/ReservationsSection.jsx";
import { useTranslation } from "react-i18next";

function ProfilePage() {
    const { t } = useTranslation();
    const { user, setUser, language } = useUser();

    const startingTabs = [
        { name: t("account"), href: "#", current: false },
        { name: t("password"), href: "#", current: false },
        { name: t("my-reservations"), href: "#", current: true },
    ];

    const [tabs, setTabs] = useState(startingTabs);

    useEffect(() => {
        setTabs(startingTabs);
    }, [t, language]);

    const fields = {
        firstname: { name: "firstname", label: t("firstname") },
        lastname: { name: "lastname", label: t("lastname") },
    };

    const changeTab = (tab) => {
        setTabs((prevTabs) =>
            prevTabs.map((prevTab) => ({
                ...prevTab,
                current: prevTab.name === tab.name,
            })),
        );
    };

    return (
        <DefaultLayout>
            {user && (
                <div className="flex justify-center w-full bg-gray-100">
                    <div
                        className="max-w-5xl w-full flex flex-col py-10 px-6"
                        style={{ minHeight: "calc(100vh - 5rem)" }}
                    >
                        <div className="px-4 sm:px-6 lg:px-0">
                            <h1 className="text-xl font-medium tracking-tight text-gray-900">
                                {t("my-account")}
                            </h1>
                        </div>
                        <div className="px-4 sm:px-6 lg:px-0">
                            <div className="py-6">
                                <div className="lg:hidden">
                                    <label
                                        htmlFor="selected-tab"
                                        className="sr-only"
                                    >
                                        Select a tab
                                    </label>
                                    <select
                                        id="selected-tab"
                                        name="selected-tab"
                                        className="mt-1 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-500 sm:text-sm sm:leading-6"
                                        onChange={(e) =>
                                            changeTab(
                                                tabs.find(
                                                    (tab) =>
                                                        tab.name ===
                                                        e.target.value,
                                                ),
                                            )
                                        }
                                    >
                                        {tabs.map((tab) => (
                                            <option
                                                key={tab.name}
                                                value={tab.name}
                                                defaultValue={tab.current}
                                            >
                                                {tab.name}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                <div className="hidden lg:block">
                                    <div className="border-b border-gray-200">
                                        <nav className="-mb-px flex space-x-8">
                                            {tabs.map((tab) => (
                                                <a
                                                    key={tab.name}
                                                    href={tab.href}
                                                    onClick={() =>
                                                        changeTab(tab)
                                                    }
                                                    className={[
                                                        tab.current
                                                            ? "border-primary-600 text-primary-600"
                                                            : "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700",
                                                        "whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium",
                                                    ].join(" ")}
                                                >
                                                    {tab.name}
                                                </a>
                                            ))}
                                        </nav>
                                    </div>
                                </div>
                                {tabs[0].current && (
                                    <div>
                                        <div className="mt-10 divide-y divide-gray-200">
                                            <div className="space-y-1">
                                                <h3 className="text-lg font-medium leading-6 text-gray-900">
                                                    {t("profile")}
                                                </h3>
                                                <p className="max-w-2xl text-sm text-gray-500">
                                                    {t(
                                                        "you-can-update-your-profile",
                                                    )}
                                                </p>
                                            </div>
                                            <div className="mt-6">
                                                <dl className="divide-y divide-gray-200">
                                                    <UserProfileField
                                                        userId={user.id}
                                                        model={fields.firstname}
                                                        value={user.firstname}
                                                        onChange={(value) =>
                                                            setUser(
                                                                (prevUser) => ({
                                                                    ...prevUser,
                                                                    firstname:
                                                                        value,
                                                                }),
                                                            )
                                                        }
                                                    />
                                                </dl>
                                            </div>
                                            <div>
                                                <dl className="divide-y divide-gray-200">
                                                    <UserProfileField
                                                        userId={user.id}
                                                        model={fields.lastname}
                                                        value={user.lastname}
                                                        onChange={(value) =>
                                                            setUser(
                                                                (prevUser) => ({
                                                                    ...prevUser,
                                                                    lastname:
                                                                        value,
                                                                }),
                                                            )
                                                        }
                                                    />
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                )}
                                {tabs[1].current && (
                                    <div>
                                        <div className="mt-10 divide-y divide-gray-200">
                                            <div className="space-y-1">
                                                <h3 className="text-lg font-medium leading-6 text-gray-900">
                                                    {t("password")}
                                                </h3>
                                                <p className="max-w-2xl text-sm text-gray-500">
                                                    {t(
                                                        "you-can-update-your-password",
                                                    )}
                                                </p>
                                            </div>
                                            <div className="mt-6">
                                                <dl className="divide-y divide-gray-200">
                                                    <PasswordChanger
                                                        userId={user.id}
                                                    />
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                )}
                                {tabs[2].current && (
                                    <div>
                                        <div className="mt-10">
                                            <ReservationsSection />
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </DefaultLayout>
    );
}

export default ProfilePage;
