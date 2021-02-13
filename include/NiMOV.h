#ifndef _NIMOV_h
#define _NIMOV_h

#if defined(ARDUINO) && ARDUINO >= 100
	#include "arduino.h"
#else
	#include "WProgram.h"
#endif

#include "PWM_ESP32.h"

#define AIN1 12
#define AIN2 18
#define BIN1 14
#define BIN2 27
#define SHORTBRAKE 1
#define COAST 3
#define CW 1
#define CCW 0

class NiMOV
{
public:
	PWM pwm1;
	PWM pwm2;
	NiMOV(uint8_t pin1, uint8_t channel1, uint8_t pin2, uint8_t channel2, double frequency, uint8_t bits_resolution, float max_speed );
	~NiMOV();
	void setSpeed(float raw_speed);
	void setDirection(boolean direction,float speed);
	void setStop(uint8_t mode);
private:
	float _max_speed;
	float _pwm;
};

#endif

