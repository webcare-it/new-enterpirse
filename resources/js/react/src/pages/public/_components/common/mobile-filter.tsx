import {
    Drawer,
    DrawerClose,
    DrawerContent,
    DrawerHeader,
    DrawerTitle,
    DrawerTrigger,
} from "@/components/ui/drawer";
import { Filter } from "lucide-react";
import { Button } from "@/components/ui/button";

export const FiltersDrawerMobile = ({
    children,
}: {
    children: React.ReactNode;
}) => {
    return (
        <Drawer>
            <DrawerTrigger asChild>
                <Button variant="outline" className="block md:hidden">
                    <Filter className="w-4 h-4 mr-2" />
                    Filters
                </Button>
            </DrawerTrigger>

            <DrawerContent>
                <DrawerHeader>
                    <DrawerTitle>Filters</DrawerTitle>
                </DrawerHeader>
                <div className="p-5 space-y-5 overflow-y-auto">
                    {children}
                    <DrawerClose asChild>
                        <button className="md:hidden py-2 px-4 bg-primary border border-primary hover:bg-primary/90 cursor-pointer text-sm w-full rounded-full text-center text-primary-foreground flex items-center justify-center gap-1">
                            See Result
                        </button>
                    </DrawerClose>
                </div>
            </DrawerContent>
        </Drawer>
    );
};
