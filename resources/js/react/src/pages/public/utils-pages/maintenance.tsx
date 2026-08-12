import { motion } from "framer-motion";
import { Button } from "@/components/ui/button";
import { RefreshCw } from "lucide-react";
import { SeoWrapper } from "@/components/common/seo-wrapper";

export const MaintenancePage = () => {
  return (
    <>
      <SeoWrapper
        title="Maintenance"
        description="We're down for maintenance"
      />
      <div className="min-h-screen w-full bg-gradient-to-br from-primary/30 via-primary/70 to-primary relative overflow-hidden">
        <div className="absolute inset-0">
          <motion.div
            className="absolute top-10 left-10 w-20 h-20 bg-primary/20 rounded"
            animate={{ scale: [1, 1.2, 1], opacity: [0.3, 0.6, 0.3] }}
            transition={{ duration: 2, repeat: Infinity }}
          />
          <motion.div
            className="absolute top-32 right-20 w-16 h-16 bg-primary/20 rounded"
            animate={{ y: [0, -20, 0] }}
            transition={{ duration: 1.5, repeat: Infinity }}
          />
          <motion.div
            className="absolute bottom-20 left-32 w-24 h-24 bg-primary/20 rounded"
            animate={{ scale: [1, 1.3, 1], opacity: [0.2, 0.5, 0.2] }}
            transition={{ duration: 3, repeat: Infinity }}
          />
          <motion.div
            className="absolute bottom-32 right-10 w-12 h-12 bg-primary/20 rounded"
            animate={{ y: [0, -15, 0], rotate: [0, 180, 360] }}
            transition={{ duration: 2.5, repeat: Infinity }}
          />
          <motion.div
            className="absolute top-1/4 left-0 w-full h-px bg-gradient-to-r from-transparent via-primary/30 to-transparent"
            animate={{ opacity: [0.3, 0.8, 0.3] }}
            transition={{ duration: 2, repeat: Infinity }}
          />
          <motion.div
            className="absolute bottom-1/4 left-0 w-full h-px bg-gradient-to-r from-transparent via-primary/30 to-transparent"
            animate={{ opacity: [0.2, 0.7, 0.2] }}
            transition={{ duration: 2.5, repeat: Infinity }}
          />
        </div>

        <div className="relative z-10 flex flex-col items-center justify-center min-h-screen px-4 md:px-0">
          <div className="text-center space-y-4 md:space-y-8 w-[95%] mx-auto">
            <motion.div className="relative">
              <motion.div
                className="text-8xl mb-6"
                animate={{ y: [0, -10, 0] }}
                transition={{ duration: 1, repeat: Infinity }}>
                🔧
              </motion.div>
              <motion.div
                className="absolute -inset-4 border-4 border-primary/30 rounded-full"
                animate={{ rotate: 360 }}
                transition={{ duration: 3, repeat: Infinity, ease: "linear" }}
              />
              <motion.div
                className="absolute -inset-6 border-2 border-primary/20 rounded-full"
                animate={{ rotate: -360 }}
                transition={{ duration: 4, repeat: Infinity, ease: "linear" }}
              />
            </motion.div>

            <motion.h1
              className="text-5xl md:text-6xl font-bold bg-gradient-to-r from-white to-white bg-clip-text text-transparent"
              animate={{ opacity: [0.8, 1, 0.8] }}
              transition={{ duration: 2, repeat: Infinity }}>
              Maintenance Mode
            </motion.h1>

            <motion.p
              className="text-xl md:text-2xl text-primary-foreground leading-relaxed"
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 1, delay: 0.2 }}>
              We're working hard to improve your experience
            </motion.p>

            <motion.p
              className="text-lg text-primary-foreground max-w-lg mx-auto"
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 1, delay: 0.4 }}>
              Our team is performing essential updates and improvements. We'll
              be back online shortly with amazing new features!
            </motion.p>

            <motion.div
              className="w-full max-w-md mx-auto mt-4"
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 1, delay: 0.6 }}>
              <div className="flex justify-between text-sm text-primary-foreground mb-2">
                <span>Progress</span>
                <motion.span
                  animate={{ opacity: [0.5, 1, 0.5] }}
                  transition={{ duration: 1.5, repeat: Infinity }}>
                  Almost there...
                </motion.span>
              </div>
              <div className="w-full bg-primary/20 rounded h-2 overflow-hidden">
                <motion.div
                  className="h-full bg-gradient-to-r from-white to-white rounded"
                  initial={{ width: "0%" }}
                  animate={{ width: ["0%", "70%", "85%"] }}
                  transition={{ duration: 3, repeat: Infinity }}
                />
              </div>
            </motion.div>

            <motion.div
              className="flex justify-center space-x-2 mt-4"
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ duration: 1, delay: 0.8 }}>
              <motion.div
                className="w-3 h-3 bg-primary rounded-full"
                animate={{ y: [0, -10, 0] }}
                transition={{ duration: 0.6, repeat: Infinity }}
              />
              <motion.div
                className="w-3 h-3 bg-primary/70 rounded-full"
                animate={{ y: [0, -10, 0] }}
                transition={{ duration: 0.6, repeat: Infinity, delay: 0.1 }}
              />
              <motion.div
                className="w-3 h-3 bg-primary/50 rounded-full"
                animate={{ y: [0, -10, 0] }}
                transition={{ duration: 0.6, repeat: Infinity, delay: 0.2 }}
              />
            </motion.div>

            <motion.div
              className="mt-12 bg-primary/20 backdrop-blur-sm rounded p-6 border border-white"
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 1, delay: 1 }}>
              <div className="flex items-center justify-center space-x-3 mb-4">
                <motion.div
                  className="w-3 h-3 bg-white rounded-full"
                  animate={{ scale: [1, 1.2, 1], opacity: [0.5, 1, 0.5] }}
                  transition={{ duration: 1.5, repeat: Infinity }}
                />
                <span className="text-white font-medium">System Status</span>
              </div>
              <div className="grid grid-cols-3 gap-4 text-center">
                <motion.div
                  className="space-y-2"
                  whileHover={{ scale: 1.05 }}
                  transition={{ type: "spring", stiffness: 300 }}>
                  <div className="text-2xl">🚀</div>
                  <div className="text-sm text-white">Performance</div>
                  <div className="text-white font-bold">Optimizing</div>
                </motion.div>
                <motion.div
                  className="space-y-2"
                  whileHover={{ scale: 1.05 }}
                  transition={{ type: "spring", stiffness: 300 }}>
                  <div className="text-2xl">🔒</div>
                  <div className="text-sm text-white">Security</div>
                  <div className="text-white font-bold">Secured</div>
                </motion.div>
                <motion.div
                  className="space-y-2"
                  whileHover={{ scale: 1.05 }}
                  transition={{ type: "spring", stiffness: 300 }}>
                  <div className="text-2xl">⚡</div>
                  <div className="text-sm text-white">Speed</div>
                  <div className="text-white font-bold">Enhancing</div>
                </motion.div>
              </div>
            </motion.div>

            <motion.div
              className="mt-8"
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 1, delay: 1.4 }}>
              <motion.div
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
                transition={{ type: "spring", stiffness: 300 }}>
                <Button
                  onClick={() => window.location.reload()}
                  className="bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary text-white font-semibold px-8 py-3 rounded transition-all duration-300"
                  size="lg">
                  <motion.div
                    animate={{ rotate: 360 }}
                    transition={{
                      duration: 2,
                      repeat: Infinity,
                      ease: "linear",
                    }}
                    className="mr-2">
                    <RefreshCw className="h-5 w-5" />
                  </motion.div>
                  Refresh Page
                </Button>
              </motion.div>
            </motion.div>
          </div>
        </div>
      </div>
    </>
  );
};
