#include <stdio.h>

float average(float x, float y)
{
	return (x + y) / 2.0f;	
}

int main(void)
{
	float value1 = 0.0F;
	float value2 = 0.0F;
	float value3 = 0.0F;
	
	printf("Enter two floating-point values separated by blanks: ");
	scanf("%f %f", &value1, &value2);
	value3 = average(value1, value2);
	printf("\nThe average is : %f\n", value3);	

	return 0;
}
